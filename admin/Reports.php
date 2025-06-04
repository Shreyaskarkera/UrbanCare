<?php
include './admin_session_validation.php';
include '../connection.php';
$conn = db_connect();

// Fetch all active locations, even if they have no complaints
$locationQuery = "
    SELECT DISTINCT p.id AS place_id, p.name 
    FROM place p
    LEFT JOIN complaints c ON c.place_id = p.id
    WHERE p.is_active = true
";

$locationResult = mysqli_query($conn, $locationQuery);
$locations = [];
if ($locationResult) {
  while ($row = mysqli_fetch_assoc($locationResult)) {
    $locations[] = $row;
  }
}


// Fetch supervisors
$supervisorQuery = "SELECT id, name FROM users WHERE role_id = 2";
$supervisorResult = mysqli_query($conn, $supervisorQuery);
$supervisors = [];
if ($supervisorResult) {
  while ($row = mysqli_fetch_assoc($supervisorResult)) {
    $supervisors[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- External Stylesheets -->
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet"> <!-- DataTables CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons -->

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

    .filters {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
      justify-content: center;
    }
  </style>
</head>

<body>
  <?php include './nav.php'; ?>
  <div class="main-content">
    <h2 class="text-center mb-4">Dashboard Overview</h2>

    <!-- Filter Row -->
    <div class="row mb-4">
      <div class="col-md-6 col-sm-12">
        <select id="locationFilter" class="form-select">
          <option value="0">Select Location</option>
          <?php foreach ($locations as $location): ?>
            <option value="<?= $location['place_id']; ?>"><?= $location['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6 col-sm-12">
        <select id="supervisorFilter" class="form-select">
          <option value="0">Select Supervisor</option>
          <?php foreach ($supervisors as $supervisor): ?>
            <option value="<?= $supervisor['id']; ?>"><?= $supervisor['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>


    <div class="row g-4">
      <!-- Summary Cards (Pending, In Progress, Resolved, Rejected) -->
      <div class="col-md-3 col-sm-6">
        <div class="card text-center p-3 shadow">
          <i class="fas fa-tasks fa-2x text-success"></i>
          <h2 class="mt-2" id="openCount">0</h2>
          <p>Pending Tasks</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-center p-3 shadow">
          <i class="fas fa-sync-alt fa-2x text-warning"></i>
          <h2 class="mt-2" id="inProgressCount">0</h2>
          <p>In Progress</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-center p-3 shadow">
          <i class="fas fa-check-circle fa-2x text-primary"></i>
          <h2 class="mt-2" id="resolvedCount">0</h2>
          <p>Resolved</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-center p-3 shadow">
          <i class="fas fa-user fa-2x text-danger"></i>
          <h2 class="mt-2" id="rejectedCount">0</h2>
          <p>Rejected</p>
        </div>
      </div>

      <div class="dashboard-container mt-4">
        <div class="chart-container">
          <canvas id="taskStatusChart"></canvas>
        </div>
        <div class="chart-container">
          <canvas id="taskTrendChart"></canvas>
        </div>
      </div>

      <!-- Complaints Table -->
      <div class="row mt-4">
        <div class="col-12">
        <table id="complaintTable" class="display" style="width:100%">
  <thead>
    <tr>
      <th>SI</th>
      <th>ID</th>
      <th>Description</th>
      <th>Status</th>
      <th>Date</th>
      <th>Location</th>
      <th>Supervisor</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

        </div>
      </div>

      <!-- Centered Button Container -->
      <!-- <div class="d-flex justify-content-center mt-4 mb-5">
    <button id="downloadReport" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dateRangeModal">Download Excel Report</button>
  </div> -->
      <!-- Download Modal -->
      <div class="modal fade" id="downloadReportModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Download Report</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <label>From Date:</label>
              <input type="date" id="startDate" class="form-control">
              <label class="mt-2">To Date:</label>
              <input type="date" id="endDate" class="form-control">
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" id="downloadExcelButton" class="btn btn-success" onclick="exportReport(event)">Export</button>

            </div>
          </div>
        </div>
      </div>

      <!-- Download Button -->
      <div class="d-flex justify-content-center mt-4 mb-5">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#downloadReportModal">Download Report</button>
        <!-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#downloadReportModal">Download Report</button> -->
      </div>
    </div>

    <!-- External Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> <!-- DataTables JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js for charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script> <!-- xlsx for Excel export -->

    <script>
      function exportReport(event) {
    event.preventDefault(); // Stop form from submitting

    const locationFilter = document.getElementById("locationFilter").value;
    const supervisorFilter = document.getElementById("supervisorFilter").value;
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;

    // Check if both start and end dates are provided
    if (!startDate || !endDate) {
        alert("Please select a valid date range.");
        return;
    }

    // Make sure the user selects only one filter: Location or Supervisor
    if (locationFilter !== "0" && supervisorFilter !== "0") {
        alert("Please select only one filter: Location or Supervisor.");
        return;
    }

    // Construct URL with selected filters
    let url = `export_excel.php?start=${startDate}&end=${endDate}`;

    // Append location filter if selected
    if (locationFilter !== "0") {
    url += `&location=${locationFilter}`;
}


    // Append supervisor filter if selected
    if (supervisorFilter !== "0") {
    url += `&supervisor_id=${supervisorFilter}`;
}


    // Trigger export by navigating to the constructed URL
    window.location.href = url;
}

    </script>



    <script>
      let taskStatusChart;
      let taskTrendChart;

      $(document).ready(function() {
        const ctx1 = document.getElementById('taskStatusChart').getContext('2d');
        const ctx2 = document.getElementById('taskTrendChart').getContext('2d');

        // Initialize bar chart
        taskStatusChart = new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: ['Pending', 'In Progress', 'Resolved', 'Rejected'],
            datasets: [{
              label: 'Complaint Status',
              data: [0, 0, 0, 0],
              backgroundColor: ['#e74c3c', '#f39c12', '#2ecc71', '#e74c3c'],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: true,
                position: 'top'
              }
            }
          }
        });

        // Initialize line chart
        taskStatusChart = new Chart(ctx2, {
          type: 'line', // Change from 'bar' to 'line'
          data: {
            labels: ['Pending', 'In Progress', 'Resolved', 'Rejected'],
            datasets: [{
              label: 'Complaint Status',
              data: [0, 0, 0, 0],
              borderColor: ['#e74c3c', '#f39c12', '#2ecc71', '#e74c3c'], // Use border color for line
              fill: false, // Don't fill the area under the line
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: true,
                position: 'top'
              }
            },
            scales: {
              x: {
                title: {
                  display: true,
                  text: 'Status' // You can modify this based on the chart's context
                }
              },
              y: {
                beginAtZero: true,
                title: {
                  display: true,
                  text: 'Count'
                }
              }
            }
          }
        });

        $('#complaintTable').DataTable({
    processing: true,
    responsive: true,
    destroy: true, // allows re-initialization
    searching: true,
    ordering: true,
    paging: true,
    info: true,
    columns: [
        { data: 'serial_number' },  // Display serial number
        { data: 'complaint_id' },
        { data: 'description' },
        { data: 'status' },
        { data: 'created_at' },
        { data: 'location_name' },
        { data: 'supervisor_name' }
    ]
});



function fetchComplaintData(location_id, supervisor_id, fromDate = '', toDate = '') {
    $.ajax({
        url: "../query/fetch_complaint_data.php", // PHP script URL to fetch the complaint data
        type: "POST", // HTTP method (POST)
        data: {
            location_id: location_id, // Pass location_id to the server
            supervisor_id: supervisor_id, // Pass supervisor_id to the server
            fromDate: fromDate, // Pass fromDate for date range filter
            toDate: toDate // Pass toDate for date range filter
        },
        dataType: "json", // Expect JSON response from the server
        success: function(response) {
            console.log(response);  // Check if serial_number is included in the response

            if (response.error) {
                alert(response.error);  // Show an error message if there's an issue
            } else {
                // Assuming you're populating the DataTable here:
                var table = $('#complaintTable').DataTable(); // Get the table instance

                table.clear();  // Clear existing data

                // Add new rows to the table based on response.complaints
                table.rows.add(response.complaints);

                // Redraw the table after adding new data
                table.draw();

                // Optionally, update any other dashboard components if needed
                updateDashboard(response); // Assuming this function updates the rest of the dashboard
            }
        },
        error: function(xhr, status, error) {
            // Show an alert if there's an AJAX request failure
            alert("Error fetching data. Please try again later.");
        }
    });
}

        function updateDashboard(response) {

          // Ensure the response contains statusCounts and complaints
          const statusCounts = response.statusCounts || {};
          const complaints = response.complaints || [];

          // Update UI elements for status counts if they exist in the response
          const openCountElement = document.getElementById('openCount');
          const inProgressCountElement = document.getElementById('inProgressCount');
          const resolvedCountElement = document.getElementById('resolvedCount');
          const rejectedCountElement = document.getElementById('rejectedCount');

          if (openCountElement) {
            openCountElement.textContent = statusCounts.Open || 0;
          }

          if (inProgressCountElement) {
            inProgressCountElement.textContent = statusCounts["In-Progress"] || 0;
          }

          if (resolvedCountElement) {
            resolvedCountElement.textContent = statusCounts.Resolved || 0;
          }

          if (rejectedCountElement) {
            rejectedCountElement.textContent = statusCounts.Rejected || 0;
          }

          // Update the complaints table (if any)
          const tableBody = document.querySelector('#complaintsTable tbody');
          if (tableBody) {
            tableBody.innerHTML = ''; // Clear existing table rows

            let serialNumber = 1; // Start serial number from 1

            // Loop through the complaints and add them to the table
            complaints.forEach((complaint) => {
              const row = document.createElement('tr');

              const serialNumberCell = document.createElement('td');
              serialNumberCell.textContent = serialNumber++; // Increment serial number
              row.appendChild(serialNumberCell);

              const complaintIdCell = document.createElement('td');
              complaintIdCell.textContent = complaint.complaint_id || 'N/A';
              row.appendChild(complaintIdCell);

              const locationNameCell = document.createElement('td');
              locationNameCell.textContent = complaint.location_name || 'Unknown Location';
              row.appendChild(locationNameCell);

              const supervisorNameCell = document.createElement('td');
              supervisorNameCell.textContent = complaint.supervisor_name || 'Unknown Supervisor';
              row.appendChild(supervisorNameCell);

              const statusCell = document.createElement('td');
              statusCell.textContent = complaint.status || 'No Status';
              row.appendChild(statusCell);

              const createdAtCell = document.createElement('td');
              createdAtCell.textContent = complaint.created_at || 'N/A';
              row.appendChild(createdAtCell);

              // Append the row to the table body
              tableBody.appendChild(row);
            });
          }

          // Update the charts with the new status counts and task trend
          updateChart(statusCounts, response.taskTrend);
        }

        function updateChart(statusCounts, taskTrendData) {
          // Update Task Status Chart
          const taskStatusChart = Chart.getChart("taskStatusChart");
          if (taskStatusChart) {
            taskStatusChart.data.datasets[0].data = [
              statusCounts.Open || 0,
              statusCounts["In-Progress"] || 0,
              statusCounts.Resolved || 0,
              statusCounts.Rejected || 0
            ];
            taskStatusChart.update();
          }

          // Update Task Status Chart
          const taskTrendChart = Chart.getChart("taskTrendChart");
          if (taskTrendChart) {
            taskTrendChart.data.datasets[0].data = [
              statusCounts.Open || 0,
              statusCounts["In-Progress"] || 0,
              statusCounts.Resolved || 0,
              statusCounts.Rejected || 0
            ];
            taskTrendChart.update();
          } else {
            // Handle case where taskTrendChart is not initialized
            console.error("taskTrendChart is not initialized.");
          }
        }

        function populateDataTable(complaints) {
          if (Array.isArray(complaints)) {
            complaintsTable.clear(); // Clear existing rows
            let serialNumber = 1;
            complaints.forEach(function(complaint) {
              complaintsTable.row.add([
                serialNumber,
                complaint.complaint_id || 'N/A',
                complaint.location_name || 'Unknown',
                complaint.supervisor_name || 'Unknown',
                complaint.status || 'Unknown',
                complaint.created_at || 'N/A'
              ]);
              serialNumber++;
            });
            complaintsTable.draw(); // Redraw the table after adding new rows
          } else {
            console.error("Invalid complaints data:", complaints);
          }
        }

        // Filter Handlers
        $('#locationFilter').change(function() {
          fetchComplaintData($('#locationFilter').val(), $('#supervisorFilter').val());
        });

        $('#supervisorFilter').change(function() {
          fetchComplaintData($('#locationFilter').val(), $('#supervisorFilter').val());
        });

        $('#dateRangeForm').submit(function(e) {
          e.preventDefault();
          let fromDate = $('#fromDate').val();
          let toDate = $('#toDate').val();

          fetchComplaintData($('#locationFilter').val(), $('#supervisorFilter').val(), fromDate, toDate);
          $('#dateRangeModal').modal('hide');
        });

        $('#downloadReport').click(function() {
          $('#dateRangeModal').modal('show');
        });

        // Initialize Filters and Fetch Data
        fetchComplaintData('', '', '', ''); // Load initial data with no filters
      });
    </script>
</body>

</html>