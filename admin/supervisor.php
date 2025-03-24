<?php include './admin_session_validation.php'; ?>

<?php
include '../connection.php'; // Include your database connection file
$conn = db_connect();

// Fetch supervisors
$query = "SELECT u.id, u.name, u.email, u.phone_no, u.is_active,
(SELECT COUNT(*) FROM complaints c WHERE c.supervisor_id = u.id) AS assigned_complaints 
FROM users u WHERE u.role_id = 2";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Insert Supervisor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_supervisor'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
    $phone = $_POST['phone'];

    $insertQuery = "INSERT INTO users (name, email, password, phone_no, role_id, is_active) VALUES (?, ?, ?, ?, 2, 1)";
    $stmtInsert = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmtInsert, "ssss", $name, $email, $password, $phone);

    if (mysqli_stmt_execute($stmtInsert)) {
        echo "<script>alert('Supervisor added successfully'); window.location.href='supervisor.php';</script>";
    } else {
        echo "<script>alert('Error adding supervisor');</script>";
    }
    mysqli_stmt_close($stmtInsert);
}

// Activate/Deactivate Supervisor
if (isset($_GET['toggle_id']) && isset($_GET['status'])) {
    $id = $_GET['toggle_id'];
    $status = $_GET['status']; // 1 for active, 0 for inactive

    $statusQuery = "UPDATE users SET is_active = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($statusQuery);
    $stmtUpdate->bind_param("ii", $status, $id);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Status updated successfully!'); window.location.href='supervisor.php';</script>";
    } else {
        echo "<script>alert('Failed to update status!');</script>";
    }
    $stmtUpdate->close(); // Close statement
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supervisor Management</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../admin/css/admin.css">
</head>

<body>
  <?php include './nav.php'; ?>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Manage Supervisors</h2>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSupervisorModal">Add Supervisor</button>
    </div>

    <table id="supervisorTable" class="table table-bordered table-striped text-center table-container">
      <thead class="table-success">
        <tr>
          <th>Sno</th>
          <th>Supervisor ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Assigned Tasks</th>
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
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone_no']; ?></td>
            <td><?php echo $row['assigned_complaints']; ?></td>
            <td>
                <?php echo ($row['is_active'] == 1) ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-danger'>Inactive</span>"; ?>
            </td>
            <td>
              <div class="d-flex justify-content-center align-items-center gap-2">

                <?php if ($row['is_active'] == 1) { ?>
                  <a href="supervisor.php?toggle_id=<?php echo $row['id']; ?>&status=0" 
                    class='btn btn-danger btn-sm'
                    onclick="return confirm('Are you sure you want to deactivate this user?');">
                    Deactivate
                  </a>
                <?php } else { ?>
                  <a href="supervisor.php?toggle_id=<?php echo $row['id']; ?>&status=1" 
                    class='btn btn-success btn-sm'
                    onclick="return confirm('Are you sure you want to activate this user?');">
                    Activate

                  </a>
                <?php } ?>
              </div>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <!-- Add Supervisor Modal -->
    <div class="modal fade" id="addSupervisorModal" tabindex="-1" aria-labelledby="addSupervisorModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addSupervisorModalLabel">Add Supervisor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="" autocomplete="off">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" required autocomplete="off">
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required autocomplete="off">
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required autocomplete="new-password">
              </div>
              <div class="mb-3">
                <label class="form-label">Phone No</label>
                <input type="text" class="form-control" name="phone" required autocomplete="off">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="add_supervisor" class="btn btn-success">Add Supervisor</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#supervisorTable').DataTable();
    });
  </script>
</body>

</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
