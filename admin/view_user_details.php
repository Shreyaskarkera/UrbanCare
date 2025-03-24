<?php include './admin_session_validation.php'; ?>
<?php include '../connection.php'; ?>

<?php
$conn = db_connect();

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Fetch user details

    $userQuery = "SELECT id, name, email, phone_no, is_active, created_at, updated_at, role_id, photo FROM users WHERE id = ?";

    $stmtUser = $conn->prepare($userQuery);
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $userResult = $stmtUser->get_result();
    $user = $userResult->fetch_assoc();

    // Fetch complaints by the user
    $complaintsQuery = "SELECT id, title, description, complaint_status, created_at FROM complaints WHERE user_id = ?";
    $stmtComplaints = $conn->prepare($complaintsQuery);
    $stmtComplaints->bind_param("i", $user_id);
    $stmtComplaints->execute();
    $complaintsResult = $stmtComplaints->get_result();
} else {
    echo "<script>alert('Invalid User ID!'); window.location.href='users.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../admin/css/admin.css">

</head>

<body>
    <?php include './nav.php'; ?> <!-- Include Navigation -->

    <div class="container main-content">
        <h2 class="text-center mb-4 text-dark fw-bold">User Details</h2>

        <!-- User Details Card -->
        <div class="card shadow-sm border-0 rounded-3 ">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3 text-center">
                        <img src="<?php echo !empty($user['photo']) ? $user['photo'] : '/asset/images/default_profile.jpg'; ?>"
                            alt="Profile Photo" class="rounded-circle border img-fluid" width="150">

                    </div>
                    <div class="col-md-9">
                        <div class="row g-3">
                            <?php
                            $fields = [
                                'User ID' => 'id',
                                'Name' => 'name',
                                'Email' => 'email',
                                'Phone' => 'phone_no',
                                'Role ID' => 'role_id',
                                'Created At' => 'created_at',
                                'Updated At' => 'updated_at'
                            ];
                            foreach ($fields as $label => $dbField) { ?>
                                <div class="col-md-4">
                                    <div class="p-2 border rounded bg-light">
                                        <strong class="text-dark"><?php echo $label; ?></strong>
                                        <p class="text-muted mb-0">
                                            <?php
                                            if ($dbField == 'role_id') {
                                                echo ($user[$dbField] == 1) ? "User" : (($user[$dbField] == 2) ? "Supervisor" : "Admin");
                                            } else {
                                                echo $user[$dbField];
                                            }
                                            ?>
                                        </p>

                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-md-4">
                                <div class="p-2 border rounded bg-light">
                                    <strong class="text-dark">Status</strong>
                                    <p class="mb-0">
                                        <span class='badge <?php echo ($user['is_active'] == 1) ? "bg-success" : "bg-danger"; ?>'>
                                            <?php echo ($user['is_active'] == 1) ? "Active" : "Blocked"; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="users.php" class="btn btn-outline-primary"><i class="fa-solid fa-arrow-left fx-2x"></i></a>
                </div>
            </div>
        </div>

        <!-- Complaints Table -->
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h4 class="text-center mb-3">Complaints Raised</h4>
                <div class="table-responsive">
                    <table id="complaintsTable" class="table table-bordered text-center w-100">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $complaintsResult->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td>
                                        <span class='badge <?php echo ($row['complaint_status'] == "Resolved") ? "bg-success" : (($row['complaint_status'] == "Pending") ? "bg-warning" : "bg-danger"); ?>'>
                                            <?php echo $row['complaint_status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#complaintsTable').DataTable();
        });
    </script>
</body>

</html>
<?php
$stmtUser->close();
$stmtComplaints->close();
$conn->close();
?>