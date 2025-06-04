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
// Default profile picture if none exists
$photo = !empty($user['photo']) ? "../" . htmlspecialchars($user['photo']) : "default_profile_picture.jpg";
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

    <style>
    body {
    overflow-x: hidden;
}

.container-fluid {
    padding-left: 15px;
    padding-right: 15px;
}
.container {
    max-width: 95%;  
    width: 100%;
    margin-left: 50px; /* Adjust this for left margin */
    margin-right: 50px; /* Add this for right margin */
}

.main-content {
    overflow-x: hidden;
    margin-right: 200px; /* Move the content slightly towards the right */
    padding-left: 20px; /* Optional: add some padding on the left for a better effect */
}

@media (max-width: 767.98px) {
    .main-content {
        padding: 1rem;
    }
}


.card {
    /* margin-bottom: 30px; */
    padding: 20px;
}

.card img {
    width: 100%;
    max-width: 220px;
    height: 220px;
    object-fit: cover;
    border-radius: 8px;
}

    </style>

</head>


<body>
    <?php include './nav.php'; ?> <!-- Include Sidebar Navigation -->

    <div class="container-fluid main-content mt-5">
        <div class="container"> <!-- Add this -->
            <div class="row ">
                <div class="col-12 col-lg-10 px-3 px-md-4 py-4">


                    <h2 class="text-center mb-4 text-dark fw-bold">User Details</h2>

                    <!-- User Details Card -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-body">
                            <div class="row g-4 align-items-center flex-column flex-md-row">
                                <div class="col-md-3 text-center">
                                    <?php
                                    $defaultImage = '../asset/images/default_profile.png';
                                    $finalPhoto = (!empty($photo) && file_exists($photo)) ? $photo : $defaultImage;
                                    ?>
                                    <img src="<?= htmlspecialchars($finalPhoto) ?>" alt="Profile Picture"
                                        class="border shadow-sm"
                                        style="width: 220px; height: 220px; object-fit: cover; border-radius: 8px;">
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
                                            <div class="col-md-4 col-sm-6">
                                                <div class="p-2 border rounded bg-light h-100">
                                                    <strong class="text-dark"><?= $label ?></strong>
                                                    <p class="text-muted mb-0">
                                                        <?= ($dbField == 'role_id')
                                                            ? (($user[$dbField] == 1) ? "User" : (($user[$dbField] == 2) ? "Supervisor" : "Admin"))
                                                            : $user[$dbField]; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="p-2 border rounded bg-light h-100">
                                                <strong class="text-dark">Status</strong>
                                                <p class="mb-0">
                                                    <span class='badge <?= ($user['is_active'] == 1) ? "bg-success" : "bg-danger" ?>'>
                                                        <?= ($user['is_active'] == 1) ? "Active" : "Blocked" ?>
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
                    <div class="card shadow-sm">
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
                                                <td><?= $row['id'] ?></td>
                                                <td><?= $row['title'] ?></td>
                                                <td><?= $row['description'] ?></td>
                                                <td>
                                                    <span class='badge <?= ($row['complaint_status'] == "Resolved") ? "bg-success" : (($row['complaint_status'] == "Pending") ? "bg-warning" : "bg-danger") ?>'>
                                                        <?= $row['complaint_status'] ?>
                                                    </span>
                                                </td>
                                                <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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