<?php
include './admin_session_validation.php';

include '../connection.php';

$conn = db_connect();

// Fetch locations from the database
$locationQuery = "SELECT id, name FROM place WHERE is_active = true";
$locationResult = mysqli_query($conn, $locationQuery);

$locations = []; // Initialize an empty array to store locations

if ($locationResult) {
  while ($row = mysqli_fetch_assoc($locationResult)) {
    $locations[] = $row;
  }
} else {
  die("Error fetching locations: " . mysqli_error($conn));
}

// Fetch supervisors from the database
$supervisorQuery = "SELECT id, name FROM users WHERE role_id = 2";
$supervisorResult = mysqli_query($conn, $supervisorQuery);

$supervisors = []; // Initialize an empty array to store supervisors

if ($supervisorResult) {
  while ($row = mysqli_fetch_assoc($supervisorResult)) {
    $supervisors[] = $row; // Store the whole row (ID and Name)
  }
} else {
  die("Error fetching supervisors: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report</title>

  <!-- External Stylesheets -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <!-- <link rel="stylesheet" href="../admin/css/admin-report.css"> -->
  <link rel="stylesheet" href="../admin/css/admin.css">
  <style>
    .dashboard-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.chart-container {
    flex: 1;
    min-width: 300px;
    height: 400px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
    </style>
</head>

<body>
  <?php include './nav.php'; ?>
  <!-- Main Content -->
  <div class="main-content">
    <h2 class="text-center mb-4">Dashboard Overview</h2>

    <!-- Filters -->
    <div class="d-flex justify-content-center mb-4">
      <select id="locationFilter" class="form-select w-25 me-2">
        <option value="0">All</option>
        <?php foreach ($locations as $location) { ?>
          <option value="<?php echo $location['id']; ?>"><?php echo $location['name']; ?></option>
        <?php } ?>
      </select>

      <select id="supervisorFilter" class="form-select w-25 me-2">
        <option value="0">All</option>
        <?php foreach ($supervisors as $supervisor) { ?>
          <option value="<?php echo $supervisor['id']; ?>"><?php echo $supervisor['name']; ?></option>
        <?php } ?>
      </select>

      <button id="downloadReport" class="btn btn-success"><i class="bi bi-download"></i> Download Report</button>
    </div>


        <!-- <h2 class="text-center mb-4">Dashboard Overview</h2> -->
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fas fa-tasks fa-2x text-success"></i>
                    <h2 class="mt-2 pending">0</h2>
                    <p>Pending Tasks</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fas fa-sync-alt fa-2x text-warning"></i>
                    <h2 class="mt-2 in-progress">0</h2>
                    <p>In Progress</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                    <h2 class="mt-2 completed">0</h2>
                    <p>Resolved</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fas fa-user fa-2x text-danger"></i>
                    <h2 class="mt-2 rejected">0</h2>
                  <p>Rejected</p>
                </div>
            </div>
        </div>


    <!-- Charts -->
    <div class="dashboard-container">
      <div class="chart-container">
        <canvas id="taskStatusChart"></canvas>
      </div>
      <div class="chart-container">
        <canvas id="taskTrendChart"></canvas>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <!-- Chart Scripts -->
  <script>
    // Task Status Bar Chart
    $(document).ready(function () {
    function updateChart(data) {
        taskStatusChart.data.datasets[0].data = [data.Open, data["In-Progress"], data.Resolved];
        taskStatusChart.update();

        taskTrendChart.data.datasets[0].data = [data.Open, data["In-Progress"], data.Resolved];
        taskTrendChart.update();
    }

    function fetchComplaintData(location_id, supervisor_id) {
        $.ajax({
            url: "../query/fetch_complaint_data.php",
            type: "POST",
            data: { "location_id": location_id ,
              "supervisor_id" : supervisor_id 
            },
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                } else {
                  console.log(response)
                  $(".pending").text(response.Open);
                  $(".in-progress").text( response["In-Progress"]);
                  $(".completed").text(response.Resolved);
                  $(".rejected").text(response.Rejected);


                    updateChart(response);
                }
            },
            error: function (e) {
              console.log(e);
                alert("Error fetching data.");
            }
        });
    }

    let location_id =  $("#locationFilter").val();
    let supervisor_id =  $("#supervisorFilter").val();
    // Fetch default data (All complaints)
    fetchComplaintData(location_id, supervisor_id);

    $("#locationFilter").change(function () {
        let location_id = $(this).val();
        let supervisor_id =  $("#supervisorFilter").val();
        console.log(location_id,supervisor_id);
        fetchComplaintData(location_id,supervisor_id);
    });

    $("#supervisorFilter").change(function () {
      let location_id =  $("#locationFilter").val();
        let supervisor_id = $(this).val();
        console.log(location_id,supervisor_id);
        fetchComplaintData(location_id, supervisor_id);
    });

    // Initialize Chart
    const ctx1 = document.getElementById('taskStatusChart').getContext('2d');
    var taskStatusChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Pending', 'In Progress', 'Completed'],
            datasets: [{
                label: 'Complaint Status',
                data: [0, 0, 0], // Default empty
                backgroundColor: ['#e74c3c', '#f39c12', '#2ecc71'],
                borderWidth: 2,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'top' } }
        }
    });

    const ctx2 = document.getElementById('taskTrendChart').getContext('2d');
    var taskTrendChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Pending', 'In Progress', 'Completed'],
            datasets: [{
                label: 'Complaint Status',
                data: [0, 0, 0], // Default empty
                backgroundColor: ['#e74c3c', '#f39c12', '#2ecc71'],
                borderWidth: 2,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'top' } }
        }
    });

});



    // Download Report as Excel
    document.getElementById('downloadReport').addEventListener('click', function() {
      let data = [
        ['Task Status', 'Count'],
        ['Pending', 4],
        ['In Progress', 6],
        ['Completed', 10]
      ];
      let ws = XLSX.utils.aoa_to_sheet(data);
      let wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Report");
      XLSX.writeFile(wb, "report.xlsx");
    });
  </script>
</body>

</html>