<?php include './admin_session_validation.php'; ?>
<?php include '../connection.php'; ?>

<?php
$conn = db_connect();

// Fetch users
$query = "SELECT u.id, u.name, u.email, u.phone_no, u.is_active, 
                 (SELECT COUNT(*) FROM complaints c WHERE c.user_id = u.id) AS total_complaints
          FROM users u WHERE u.role_id = 1"; // Assuming role_id 3 is for normal users

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Toggle User Status (Block/Unblock)
if (isset($_GET['toggle_id']) && isset($_GET['status'])) {
    $id = $_GET['toggle_id']; // User ID
    $status = $_GET['status']; // 1 for active, 0 for blocked

    $statusQuery = "UPDATE users SET is_active = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($statusQuery);
    $stmtUpdate->bind_param("ii", $status, $id); // Corrected query with dynamic ID

    if ($stmtUpdate->execute()) {
        echo "<script>alert('User status updated successfully!'); window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Failed to update status!');</script>";
    }
    $stmtUpdate->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../admin/css/admin.css">
</head>

<body>
  <?php include './nav.php'; ?>

  <div class="main-content">
    <h2 class="mb-4 b">User Management</h2>

    <table id="userTable" class="table table-bordered table-striped text-center table-container">
      <thead class="table-success">
        <tr>
          <th>Sno</th>
          <th>User ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Total Complaints</th>
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
            <td><?php echo $row['total_complaints']; ?></td>
            <td>
                <?php echo ($row['is_active'] == 1) ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-danger'>Blocked</span>"; ?>
            </td>
            <td>
              <div class="d-flex justify-content-center align-items-center gap-2">
                <a href="view_user_details.php?user_id=<?php echo $row['id']; ?>" class='btn btn-info btn-sm'>View</a>

                <?php if ($row['is_active'] == 1) { ?>
                  <a href="users.php?toggle_id=<?php echo $row['id']; ?>&status=0" 
                    class='btn btn-danger btn-sm ps-3 pe-3'
                    onclick="return confirm('Are you sure you want to block this user?');">
                    Block
                  </a>
                <?php } else { ?>
                  <a href="users.php?toggle_id=<?php echo $row['id']; ?>&status=1" 
                    class='btn btn-success btn-sm'
                    onclick="return confirm('Are you sure you want to unblock this user?');">
                    Unblock
                  </a>
                <?php } ?>
              </div>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#userTable').DataTable();
    });
  </script>
</body>

</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
