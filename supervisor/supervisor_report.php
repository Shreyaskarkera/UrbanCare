<?php
session_start();
include '../connection.php'; // Database connection

$conn = db_connect();

// Check if supervisor is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$supervisor_id = $_SESSION['user_id'];

// Fetch supervisor name
$query = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supervisor_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$name = $row['name'] ?? 'Unknown Supervisor';

// Fetch assigned place
$query = "SELECT place_id FROM supervisor_map WHERE supervisor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supervisor_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("No place assigned to this supervisor.");
}

$place_id = $row['place_id'];

// Fetch complaint statistics
$stats = [
    'total' => 0,
    'resolved' => 0,
    'pending' => 0,
    'in_progress' => 0,
    'rejected' => 0
];

$query = "SELECT complaint_status, COUNT(*) AS count FROM complaints  
          WHERE place_id = ? AND supervisor_id = ?  
          GROUP BY complaint_status";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $place_id, $supervisor_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $stats['total'] += $row['count'];
    if ($row['complaint_status'] == 'Resolved') {
        $stats['resolved'] = $row['count'];
    } elseif ($row['complaint_status'] == 'In Progress') {
        $stats['in_progress'] = $row['count'];
    } elseif ($row['complaint_status'] == 'Rejected') {
        $stats['rejected'] = $row['count'];
    } else {
        $stats['pending'] = $row['count'];
    }
}

// Fetch complaint details
$query = "SELECT * FROM complaints WHERE place_id = ? AND supervisor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $place_id, $supervisor_id);
$stmt->execute();
$complaints = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Report</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="css/nav.css">
    <style>
 .chart-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}

.chart-container {
    width: 100%;
    max-width: 600px;
    height: 400px;
    position: relative;
}

@media (max-width: 768px) {
    .chart-container {
        max-width: 90%;
        height: 350px;
        padding: 10px;
    }
}

.stats-card {
    padding: 50px;
    color: #fff;
    font-size: 1.1rem;
    border-radius: 8px;
    background-color: #444;
    margin-bottom: 15px;
    font-weight: bold;
}

.resolved { background-color: #28a745; }
.pending { background-color: #ff9800; }
.in-progress { background-color: #007bff; }
.rejected { background-color: #dc3545; }

.btn-switch {
    margin: 5px;
}

@media (max-width: 768px) {
    .stats-card {
        font-size: 0.9rem;
        padding: 15px;
    }

    .chart-container {
        padding: 10px;
    }

    canvas {
        width: 100% !important;
        height: auto !important;
    }

    .btn-switch {
        font-size: 0.9rem;
        padding: 6px 12px;
    }
}

    </style>
</head>
<body>
<?php include './nav.php'; ?>
<div class="container-fluid px-4">
    <h2 class="text-center my-4">Supervisor Report</h2>

    <!-- Complaint Stats Cards -->
    <div class="row text-center mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="stats-card resolved">Resolved: <?= $stats['resolved'] ?></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card pending">Pending: <?= $stats['pending'] ?></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card in-progress">In Progress: <?= $stats['in_progress'] ?></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card rejected">Rejected: <?= $stats['rejected'] ?></div>
        </div>
    </div>

    <!-- Chart Section -->
<!-- Chart Section -->
<div class="text-center mb-3 d-md-none">
    <button class="btn btn-outline-light btn-switch" onclick="toggleChart('pie')">Pie Chart</button>
    <button class="btn btn-outline-light btn-switch" onclick="toggleChart('line')">Line Chart</button>
</div>

<div class="row chart-wrapper">
    <div class="col-md-6 chart-container" id="pieChartContainer">
        <canvas id="pieChart"></canvas>
    </div>
    <div class="col-md-6 chart-container" id="lineChartContainer">
        <canvas id="lineChart"></canvas>
    </div>
</div>


    <!-- Complaint Details Table -->
    <h3 class="mt-4">Complaint Details</h3>
    <div class="table-responsive">
        <table id="complaintTable" class="table table-striped table-dark">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $complaints->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['complaint_status']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Download Modal -->
    <div class="modal fade" id="downloadReportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
<<<<<<< HEAD
                    <h5 class="modal-title">Download Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
=======
                    <h5 class="modal-title text-dark">Download Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-dark">
>>>>>>> 963cf97d0c76debcafe1ed9557be3be99da14b2d
                    <label>From Date:</label>
                    <input type="date" id="startDate" class="form-control">
                    <label class="mt-2">To Date:</label>
                    <input type="date" id="endDate" class="form-control">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" onclick="exportReport()">Download Excel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Button -->
    <div class="text-center my-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#downloadReportModal">Download Report</button>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function exportReport() {
        var startDate = document.getElementById("startDate").value;
        var endDate = document.getElementById("endDate").value;
        if (startDate === "" || endDate === "") {
            alert("Please select a valid date range.");
            return;
        }
        window.location.href = 'export_excel.php?start=' + startDate + '&end=' + endDate;
    }
</script>
<script>
    var pieCtx = document.getElementById('pieChart').getContext('2d');
    var lineCtx = document.getElementById('lineChart').getContext('2d');
    var pieChart, lineChart;

    function createPieChart() {
        pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Resolved', 'Pending', 'In Progress', 'Rejected'],
                datasets: [{
                    data: [<?= $stats['resolved'] ?>, <?= $stats['pending'] ?>, <?= $stats['in_progress'] ?>, <?= $stats['rejected'] ?>],
                    backgroundColor: ['#28a745', '#ff9800', '#007bff', '#dc3545'],
                    borderColor: ['#71c971', '#f8b26a', '#6fa8dc', '#e57373']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    function createLineChart() {
        lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Resolved', 'Pending', 'In Progress', 'Rejected'],
                datasets: [{
                    label: 'Complaints',
                    data: [<?= $stats['resolved'] ?>, <?= $stats['pending'] ?>, <?= $stats['in_progress'] ?>, <?= $stats['rejected'] ?>],
                    backgroundColor: '#007bff33',
                    borderColor: '#007bff',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    function toggleChart(type) {
        if (window.innerWidth <= 768) {
            document.getElementById('pieChartContainer').style.display = (type === 'pie') ? 'block' : 'none';
            document.getElementById('lineChartContainer').style.display = (type === 'line') ? 'block' : 'none';
        }
    }

    // Initialize charts
    createPieChart();
    createLineChart();

    // Responsive toggle behavior
    window.addEventListener('load', () => {
        if (window.innerWidth <= 768) {
            document.getElementById('lineChartContainer').style.display = 'none'; // default: show pie
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            document.getElementById('pieChartContainer').style.display = 'block';
            document.getElementById('lineChartContainer').style.display = 'block';
        } else {
            toggleChart('pie'); // default pie chart on small screen
        }
    });
</script>
</body>
</html>