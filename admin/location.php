<?php
session_start(); // Start the session

include '../connection.php'; // Include database connection file

// Check if user is logged in and has the correct role
<<<<<<< HEAD
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) { // Assuming role_id 1 is for ADMIN
=======
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
>>>>>>> 963cf97d0c76debcafe1ed9557be3be99da14b2d
  echo "<script>alert('You don’t have access to this page'); window.location.href = '../login.php';</script>";
  exit();
}
$name = $_SESSION['name'];
$conn = db_connect();
if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission to add location
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
  $location_name = trim($_POST['name']);
  $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

  // Check if location already exists
  $check_query = "SELECT id FROM place WHERE name = ?";
  $check_stmt = mysqli_prepare($conn, $check_query);
  mysqli_stmt_bind_param($check_stmt, "s", $location_name);
  mysqli_stmt_execute($check_stmt);
  $check_result = mysqli_stmt_get_result($check_stmt);

  if (mysqli_num_rows($check_result) > 0) {
    echo "<script>alert('Location already exists.'); window.location.href='location.php';</script>";
    exit();
  }

  // Insert location
  $insert_query = "INSERT INTO place (name, is_active) VALUES (?, ?)";
  $insert_stmt = mysqli_prepare($conn, $insert_query);
  mysqli_stmt_bind_param($insert_stmt, "si", $location_name, $status);
  if (mysqli_stmt_execute($insert_stmt)) {
    echo "<script>alert('Location added successfully'); window.location.href='location.php';</script>";
  } else {
    echo "<script>alert('Failed to add location'); window.location.href='location.php';</script>";
  }
}

// Fetch all locations
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

  <link rel="stylesheet" href="../admin/css/admin.css">
</head>

<body>
  <?php include './nav.php'; ?>
  <div class="container main-content d-flex flex-column" id="location-nav">
    <div class="d-flex justify-content-between w-100 mb-3">
      <h2 class="m-0">Manage Locations</h2>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addLocationModal">
        <i class="bi bi-plus-lg"></i> Add Location
      </button>
    </div>

    <div class="table-responsive">
      <table id="locationTable" class="table table-bordered table-striped text-center table-container">
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
            <button type="submit" class="btn btn-success">Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#locationTable').DataTable({
        "responsive": true,
        "autoWidth": true,  /* Disable auto width */
        "columnDefs": [
          { "targets": '_all', "className": 'dt-left' }  /* Left-align all columns */
        ]
      });

      // Fill Edit Modal with current data
      $('#locationTable').on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');
        
        $('#editLocationId').val(id);
        $('#editLocationName').val(name);
        $('#editLocationStatus').val(status);
      });
    });
  </script>
</body>

</html>
