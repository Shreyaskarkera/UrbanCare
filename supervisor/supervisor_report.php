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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <style>
        body {
            background-color: #1e1e2f;
            color: #e0e0e0;
        }

        .navbar {
            background-color: #3c4a59;
            /* Dark grey with blue undertones */
        }

        .navbar-toggler-icon {
            background-color: #3c4a59;
            /* Light blue toggle icon */
        }

        .navbar-nav .nav-link,
        .navbar-brand {
            color: #f5f5f5;
            /* Soft white text */
        }

        .navbar-nav .nav-link:hover,
        .navbar-brand:hover {
            color: #3498db;
            /* Light Blue Hover */
        }


        .container {
            margin-top: 80px;
            background: #2a2d3e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .chart-container {
            max-width: 450px;
            margin: auto;
        }

        .stats-card {
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            color: rgba(255, 255, 255, 0.8);
            /* Light white text */
        }

        .resolved {
            background: #28a745;
        }

        .pending {
            background: #ff9800;
        }

        .in-progress {
            background: #007bff;
        }

        .rejected {
            background: #dc3545;
        }

        .btn-switch {
            width: 120px;
        }

        /* Lighten "Show Entries" and "Search" Text */
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            color: #bbbbbb !important;
            /* Light grey for better contrast */
            font-weight: 500;
        }

        /* Keep Dropdown & Search Input Dark */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background-color: #2b2f37;
            /* Dark background */
            color: #ffffff;
            /* White text */
            border: 1px solid #444;
            /* Subtle border */
        }

        /* DataTable Search and Controls */
        .dataTables_wrapper .dataTables_filter input {
            background-color: #3c3f45;
            /* Dark grey but softer */
            color: #e0e0e0;
            /* Light text */
            border: 1px solid #555;
            /* Soft border */
            padding: 8px;
            border-radius: 5px;
        }

        /* DataTable Pagination */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #444;
            /* Dark but soft */
            color: #ddd !important;
            /* Light text */
            border-radius: 4px;
            padding: 6px 10px;
            margin: 3px;
            transition: background 0.3s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #555;
            /* Slightly lighter on hover */
        }

        /* DataTable Dropdowns */
        .dataTables_wrapper .dataTables_length select {
            background-color: #3c3f45;
            color: #e0e0e0;
            border: 1px solid #555;
            padding: 6px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Urban Care</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php"><i
                                class="fas fa-home me-2"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./supervisor_report.php"><i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="supervisor_map.html"><i class="fas fa-map-marker-alt me-2"></i>Map</a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Preferences</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li> -->
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="supervisor_notification.html">
                            <i class="fas fa-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>Notification
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i><?php echo $name ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#viewProfileModal">View Profile</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#updateProfileModal">Update Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>


    <div class="container">
        <h2 class="text-center mb-4">Supervisor Report</h2>

        <!-- Complaint Stats Cards -->
        <div class="row text-center mb-3">
            <div class="col-md-3 ">
                <div class="stats-card  resolved">Resolved: <?= $stats['resolved'] ?></div>
            </div>
            <div class="col-md-3">
                <div class="stats-card pending">Pending: <?= $stats['pending'] ?></div>
            </div>
            <div class="col-md-3">
                <div class="stats-card in-progress">In Progress: <?= $stats['in_progress'] ?></div>
            </div>
            <div class="col-md-3">
                <div class="stats-card rejected">Rejected: <?= $stats['rejected'] ?></div>
            </div>
        </div>

        <!-- Chart Toggle Buttons -->
        <div class="text-center mb-3">
            <button class="btn btn-outline-light btn-switch" onclick="switchChart('pie')">Pie Chart</button>
            <button class="btn btn-outline-light btn-switch" onclick="switchChart('line')">Line Chart</button>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <canvas id="complaintChart"></canvas>
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
        <!-- Report Download Modal -->
<div class="modal fade" id="downloadReportModal" tabindex="-1" aria-labelledby="downloadReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadReportModalLabel">Download Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="startDate">From Date:</label>
                <input type="date" id="startDate" class="form-control">

                <label for="endDate" class="mt-2">To Date:</label>
                <input type="date" id="endDate" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="exportReport()">Download Excel</button>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center align-items-center">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#downloadReportModal">
        Download Report
    </button>
</div>
<!-- Bootstrap Bundle with Popper.js (Required for modals) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function exportReport() {
        var startDate = document.getElementById("startDate").value;
        var endDate = document.getElementById("endDate").value;
console.log(startDate);
console.log(endDate);
        if (startDate === "" || endDate === "") {
            alert("Please select a valid date range.");
            return;
        }

        window.location.href = 'export_excel.php?start=' + startDate + '&end=' + endDate;
    }
</script>

        <script>
            $(document).ready(function() {
                $('#complaintTable').DataTable();
            });

            var ctx = document.getElementById('complaintChart').getContext('2d');
            var currentChart;

            function createChart(type) {
                if (currentChart) {
                    currentChart.destroy();
                }

                currentChart = new Chart(ctx, {
                    type: type,
                    data: {
                        labels: ['Resolved', 'Pending', 'In Progress', 'Rejected'],
                        datasets: [{
                            data: [<?= $stats['resolved'] ?>, <?= $stats['pending'] ?>, <?= $stats['in_progress'] ?>, <?= $stats['rejected'] ?>],
                            backgroundColor: ['#28a745', '#ff9800', '#007bff', '#dc3545'],
                            color: ['#FFFFFF'],
                            borderColor: ['#71c971', '#f8b26a', '#6fa8dc', '#e57373']
                        }]
                    }
                });
            }

            function switchChart(type) {
                createChart(type);
            }

            switchChart('pie');

            function exportPDF() {
                window.location.href = 'export_pdf.php';
            }

            function exportExcel() {
                window.location.href = 'export_excel.php';
            }
        </script>

</body>

</html>