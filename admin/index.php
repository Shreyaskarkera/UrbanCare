<?php
session_start();
include '../connection.php';

// Check session and admin role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_name'] != 'ADMIN') {
    echo "<script>alert('You don’t have access to this page'); window.location.href = '../login.php';</script>";
    exit();
}

$name = $_SESSION['name'];
$conn = db_connect();

// Get all complaints with Supervisor ID
$sql = "SELECT c.*, sm.supervisor_id FROM complaints c LEFT JOIN supervisor_map sm ON c.place_id = sm.place_id";
$result = $conn->query($sql);

// Fetch counts
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints"))['total'];
$open = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS open FROM complaints WHERE complaint_status = 'Open'"))['open'];
$in_progress = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS in_progress FROM complaints WHERE complaint_status = 'In-Progress'"))['in_progress'];
$resolved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS resolved FROM complaints WHERE complaint_status = 'Resolved'"))['resolved'];
$rejected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS rejected FROM complaints WHERE complaint_status = 'Rejected'"))['rejected'];
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
    
    <style>
        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #28a745;
            padding-top: 20px;
            color: white;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.hidden {
            transform: translateX(-260px);
        }

        .toggle-btn {
            position: absolute;
            top: 15px;
            left: 260px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .toggle-btn:hover {
            background: #218838;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px;
            display: block;
            transition: 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: #218838;
            border-radius: 5px;
        }

        /* Navbar Styling */
        .top-nav {
            background-color: #28a745;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar.hidden + .top-nav {
            margin-left: 0;
        }

        .top-nav .user-info {
            display: flex;
            align-items: center;
        }
        
        .top-nav img {
            width: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar.hidden + .top-nav + .main-content {
            margin-left: 0;
        }

        /* Dashboard Cards */
        .card {
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #fff;
            color: #333;
            padding: 20px;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .heading{
            margin-left: 5%;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
    <button class="toggle-btn" id="toggleSidebar">☰</button>
    <h2 class="text-center">Urban Care</h2>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="./Tasks.html">Tasks</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="./Reports.html">Reports</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="./supervisor.php">Supervisors</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="./location.php">Location</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="./complaint_type.php">Complaint Type</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="./allocate_supervisor.php">Allocate Supervisor</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Logout</a></li>
    </ul>
</div>
<div class="top-nav">
            <h3 class="heading">Admin Dashboard</h3>
            <div class="user-info">
                <img src="profile.jpg" alt="Profile Picture">
                <span><?php echo $name; ?></span>
            </div>
        </div>
    <!-- Main Content -->
    <div class="main-content">
        <h2 class="text-center mb-4">Dashboard Overview</h2>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fas fa-tasks fa-2x text-success"></i>
                    <h2 class="mt-2"><?php echo $total; ?></h2>
                    <p>Total Complaints</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fas fa-sync-alt fa-2x text-warning"></i>
                    <h2 class="mt-2"><?php echo $in_progress; ?></h2>
                    <p>In Progress</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                    <h2 class="mt-2"><?php echo $resolved; ?></h2>
                    <p>Resolved</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center p-3 shadow">
                    <i class="fas fa-user fa-2x text-danger"></i>
                    <h2 class="mt-2"><?php echo $supervisors; ?></h2>
                    <a href="supervisor.html" class="text-dark"><p>Supervisor</p></a>
                </div>
            </div>
        </div>

        <!-- Complaints Table -->
        <div class="container mt-5">
            <h3 class="text-center mb-3">All Registered Complaints</h3>
            <table class="table table-bordered table-hover table-striped" id="complaintsTable">
                <thead>
                    <tr>
                        <th>SNO</th>
                        <th>Complaint ID</th>
                        <th>User ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Supervisor ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $serial_no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$serial_no++ ."</td>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["user_id"] . "</td>"; 
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
                        echo "<td>" . $row["created_at"] . "</td>";
                        echo "<td>" . $row["complaint_status"] . "</td>";
                        echo "<td>" . ($row["supervisor_id"] ? $row["supervisor_id"] : "Not Assigned") . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButton = document.getElementById("toggleSidebar");
            const sidebar = document.getElementById("sidebar");
            const topNav = document.querySelector(".top-nav");
            const mainContent = document.querySelector(".main-content");

            toggleButton.addEventListener("click", function () {
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
