<?php
include '../connection.php';
$conn = db_connect();

if (isset($_GET['supervisor_id'])) {
    $supervisor_id = $_GET['supervisor_id'];

    $query = "SELECT id, description, complaint_status, created_at FROM complaints WHERE supervisor_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) { 
        die("Query preparation failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $supervisor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Tasks</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/css/admin.css">

    

</head>
<body>

<?php include './nav.php'; ?>

    <div class="container mt-4">
        <h2>Supervisor's Assigned Tasks</h2>
        
        <table id="tasksTable" class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>Complaint ID</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['complaint_status']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="supervisor_management.php" class="btn btn-secondary mt-3">Back</a>
    </div>

    <script>
        $(document).ready(function() {
            $('#tasksTable').DataTable(); // Initialize DataTables
        });
    </script>
</body>
</html>
