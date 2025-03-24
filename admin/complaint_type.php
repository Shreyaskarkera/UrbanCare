<?php
include '../connection.php'; // Database connection
session_start(); // Start the session

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) { // Assuming role_id 1 is for ADMIN
    echo "<script>alert('You don’t have access to this page'); window.location.href = '../login.php';</script>";
    exit();
}
$name = $_SESSION['name'];
$conn = db_connect();

// Fetch complaint types
$query = "SELECT id, name, is_active FROM complaint_type";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Handle form submission to add a complaint type
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_complaint_type'])) {
    $name = $_POST['complaint_name'];
    $is_active = $_POST['is_active'];
    
    $insert_query = "INSERT INTO complaint_type (name, is_active) VALUES (?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "si", $name, $is_active);
    mysqli_stmt_execute($insert_stmt);
    mysqli_stmt_close($insert_stmt);
    
    header("Location: complaint_type.php"); // Refresh page
    exit();
}

// Handle form submission to update a complaint type
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_complaint_type'])) {
    $id = $_POST['complaint_id'];
    $name = $_POST['edit_complaint_name'];
    $is_active = $_POST['edit_is_active'];
    
    $update_query = "UPDATE complaint_type SET name = ?, is_active = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "sii", $name, $is_active, $id);
    mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);
    
    header("Location: complaint_type.php"); // Refresh page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaint Types</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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
        .container{
          margin-left: 20%;
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
    <button class="toggle-btn" id="toggleSidebar"></button>
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

    <div class="container mt-4">
        <h2>Manage Complaint Types</h2>
        <div class="d-flex justify-content-end">
            <button class="btn btn-success mb-3" onclick="openAddModal()">Add Complaint Type</button>
        </div>
        <table id="complaintTypeTable" class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) { 
                    $status = $row['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$status}</td>
                            <td>
                                <button class='btn btn-warning btn-sm' onclick=\"openEditModal({$row['id']}, '{$row['name']}', {$row['is_active']})\">Edit</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Complaint Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="complaint_name" class="form-label">Name</label>
                            <input type="text" id="complaint_name" name="complaint_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Active Status</label>
                            <select id="is_active" name="is_active" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" name="add_complaint_type" class="btn btn-success">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Complaint Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" id="complaint_id" name="complaint_id">
                        <div class="mb-3">
                            <label for="edit_complaint_name" class="form-label">Name</label>
                            <input type="text" id="edit_complaint_name" name="edit_complaint_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_is_active" class="form-label">Active Status</label>
                            <select id="edit_is_active" name="edit_is_active" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" name="update_complaint_type" class="btn btn-warning">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#complaintTypeTable').DataTable();
        });

        function openAddModal() {
            $('#addModal').modal('show');
        }

        function openEditModal(id, name, status) {
            $('#complaint_id').val(id);
            $('#edit_complaint_name').val(name);
            $('#edit_is_active').val(status);
            $('#editModal').modal('show');
        }
    </script>
</body>
</html>

<?php 
mysqli_stmt_close($stmt);
mysqli_close($conn); 
?>
