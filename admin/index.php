<?php include './admin_session_validation.php'; ?>

<?php
include '../connection.php';

$conn = db_connect();

// Get all complaints with Supervisor ID
$sql = "SELECT c.*, sm.supervisor_id FROM complaints c LEFT JOIN supervisor_map sm ON c.place_id = sm.place_id";
$result = $conn->query($sql);

// Fetch counts
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints"))['total'];
$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users WHERE role_id = 1;"));
$in_progress = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS in_progress FROM complaints WHERE complaint_status = 'In-Progress'"))['in_progress'];
$resolved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS resolved FROM complaints WHERE complaint_status = 'Resolved'"))['resolved'];
$location = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS active_locations FROM place WHERE is_active = 1"));
$total_locations = $location['active_locations'];
$supervisors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS supervisors FROM users WHERE role_id = 2"))['supervisors'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Urban Care</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/css/admin.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

    <style>
        a {
            text-decoration: none;
            color: inherit;
        }

        #container-table-size {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php include './nav.php'; ?>
   
    <div class="main-content">
        <h2 class="text-center mb-4">Dashboard Overview</h2>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fa-sharp fa-solid fa-users  fa-2x" style="color: #007bff;"></i>
                    <h2 class="mt-2"><?php echo $users['total_users']; ?></h2>
                    <a href="users.php">
                        <p>User</p>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fa-solid fa-helmet-safety fa-2x" style="color:rgb(255, 196, 3);"></i></i>
                    <h2 class="mt-2"><?php echo $supervisors; ?></h2>
                    <a href="supervisor.php">
                        <p>Supervisors</p>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fa-solid fa-location-dot fa-2x" style="color: #6f42c1;"></i>
                    <h2 class="mt-2"><?php echo  $total_locations; ?></h2>
                    <a href="location.php">
                        <p>Locations</p>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fa-solid fa-triangle-exclamation fa-2x" style="color:rgb(247, 23, 23);"></i>
                    <h2 class="mt-2"><?php echo $total; ?></h2>

                    <p>Total Complaints</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Complaints Table -->

        <div class="container mt-5" id="container-table-size">
            <h3 class="text-center mb-3">All Registered Complaints</h3>

            <table class="table table-bordered table-hover table-striped" id="complaintsTable">
                <thead class="table-success">
                    <tr>
                        <th>SNO</th>
                        <th>Complaint ID</th>
                        <th>User ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Supervisor ID</th>
                        <th>Action</th> <!-- New column -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $serial_no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $serial_no++ . "</td>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
                        echo "<td>" . $row["created_at"] . "</td>";
                        echo "<td>" . $row["complaint_status"] . "</td>";
                        echo "<td>" . ($row["supervisor_id"] ? $row["supervisor_id"] : "Not Assigned") . "</td>";

                        // View Button
                        echo "<td>
        <a href='view_complaint.php?id=" . $row["id"] . "' class='btn btn-sm btn-primary'>
            <i class='bi bi-eye'></i> View
        </a>
      </td>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggleButton = document.getElementById("toggleSidebar");
                const sidebar = document.getElementById("sidebar");
                const topNav = document.querySelector(".top-nav");
                const mainContent = document.querySelector(".main-content");

                toggleButton.addEventListener("click", function() {
                    sidebar.classList.toggle("hidden");
                    topNav.classList.toggle("expanded");
                    mainContent.classList.toggle("expanded");
                });
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#complaintsTable').DataTable();
            });
        </script>
</body>

</html>

<?php db_close($conn); ?>