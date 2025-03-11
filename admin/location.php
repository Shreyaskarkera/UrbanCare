<?php
session_start(); // Start the session

include '../connection.php'; // Include database connection file

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) { // Assuming role_id 1 is for ADMIN
    echo "<script>alert('You don’t have access to this page'); window.location.href = '../login.php';</script>";
    exit();
}
$name = $_SESSION['name'];
$conn = db_connect();
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch all locations from the `place` table
$query = "SELECT id, name, is_active FROM place";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    die("Error preparing query: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Location Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <style>
    body { background-color: #f8f9fa; }
    .navbar { box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); }
    .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); }
    .status-badge { font-weight: bold; padding: 5px 10px; border-radius: 5px; }
    .active-status { background-color: #28a745; color: white; }
    .inactive-status { background-color: #dc3545; color: white; }

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

       
        .container{
          margin-left: 20%;
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

     

        .sidebar.hidden + .top-nav + .main-content {
            margin-left: 0;
        }
    </style>
</head>
<body>
   <!-- Sidebar -->
   <div class="sidebar" id="sidebar">
    <h2 class="text-center fw-bold">Urban Care</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white fs-5" href="#"><i class="bi bi-house-door me-2"></i>Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-5" href="./Tasks.html"><i class="bi bi-list-task me-2"></i>Tasks</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-5" href="./Reports.html"><i class="bi bi-file-earmark-text me-2"></i>Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-5" href="./supervisor.php"><i class="bi bi-people me-2"></i>Supervisors</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-5" href="./location.php"><i class="bi bi-geo-alt me-2"></i>Location</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-5" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
        </li>
    </ul>
</div>

<!-- Top Navigation -->
<div class="top-nav d-flex justify-content-between align-items-center p-3 text-white">
    <h3 class="heading fw-bold"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h3>
    <div class="user-info d-flex align-items-center">
        <img src="profile.jpg" alt="Profile Picture" class="rounded-circle" width="40" height="40">
        <span class="ms-2 fs-5 fw-semibold"><?php echo $name; ?></span>
    </div>
</div>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Manage Locations</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addLocationModal">
            <i class="bi bi-plus-lg"></i> Add Location
        </button>
    </div>

    <table id="locationTable" class="table table-bordered table-striped text-center">
      <thead class="table-success">
        <tr>
          <th>Sno</th>
          <th>Location ID</th>
          <th>Location Name</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sno = 1;
        while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?php echo $sno++; ?></td>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td>
              <span class="status-badge <?php echo ($row['is_active'] == 1) ? 'active-status' : 'inactive-status'; ?>">
                <?php echo ($row['is_active'] == 1) ? 'Active' : 'Inactive'; ?>
              </span>
            </td>
            <td>
              <button class='btn btn-warning btn-sm edit-btn' 
                      data-id='<?php echo $row['id']; ?>' 
                      data-name='<?php echo $row['name']; ?>' 
                      data-status='<?php echo $row['is_active']; ?>' 
                      data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
</div>

<!-- Add Location Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLocationModalLabel">Add Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLocationForm" method="POST" action="add_location.php">
                    <div class="mb-3">
                        <label for="addLocationName" class="form-label">Location Name</label>
                        <input type="text" class="form-control" name="name" id="addLocationName" required>
                    </div>
                    <div class="mb-3">
                        <label for="addLocationStatus" class="form-label">Status</label>
                        <select class="form-control" name="status" id="addLocationStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Add Location</button>
                </form>
            </div>
        </div>
    </div>
</div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Location</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm" method="POST" action="update_location.php">
            <input type="hidden" name="id" id="editLocationId">
            <div class="mb-3">
              <label for="editLocationName" class="form-label">Location Name</label>
              <input type="text" class="form-control" name="name" id="editLocationName" required>
            </div>
            <div class="mb-3">
              <label for="editLocationStatus" class="form-label">Status</label>
              <select class="form-control" name="status" id="editLocationStatus">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            <button type="submit" class="btn btn-success" >Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#locationTable').DataTable();

      // Load data into modal
      $('.edit-btn').click(function() {
        $('#editLocationId').val($(this).data('id'));
        $('#editLocationName').val($(this).data('name'));
        $('#editLocationStatus').val($(this).data('status'));
      });
    });
  </script>
</body>
</html>

<?php 
mysqli_stmt_close($stmt);
mysqli_close($conn); 
?>
