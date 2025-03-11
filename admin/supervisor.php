<?php
include '../connection.php'; // Include your database connection file

$conn=db_connect();
// $query = "SELECT id, name, email, phone_no FROM users WHERE role_id = 2";
// $stmt = mysqli_prepare($conn, $query);
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);


$query = "SELECT u.id, u.name, u.email, u.phone_no, 
(SELECT COUNT(*) FROM complaints c WHERE c.supervisor_id = u.id) AS assigned_complaints 
FROM users u 
WHERE u.role_id = 2";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supervisor Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <style>
    body { background-color: #f8f9fa; }
    .navbar { box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); }
    .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Supervisor Management</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="dashboard.html">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="Tasks.html">Tasks</a></li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Manage Supervisors</h2>
      <a href="./add_supervisor.php" class="btn btn-success">Add Supervisor</a>
    </div>
    
    <table id="supervisorTable" class="table table-bordered table-striped">
      <thead class="table-success">
        <tr>
          <th>Sno</th>
          <th>Supervisor ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Assigned Tasks</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        
        <?php
        $sno=1;
         while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?php echo $sno++ ?></td>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone_no']; ?></td>
            <td><?php echo $row['assigned_complaints']; ?></td> <!-- Placeholder for assigned tasks -->
            <td>
              <button class='btn btn-primary btn-sm'>View Tasks</button>
              <button class='btn btn-warning btn-sm'>Edit</button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

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
