<?php
include './admin_session_validation.php';
include '../connection.php';

$conn = db_connect(); // Connect to database

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='container mt-5 text-center text-danger'><h4>Invalid complaint ID.</h4></div>";
    exit;
}

$complaint_id = intval($_GET['id']);

$sql = "SELECT 
            c.*, 
            u.name AS user_name, 
            u.phone_no AS user_phone, 
            u.email AS user_email, 
            p.name AS place_name  
        FROM complaints c
        JOIN users u ON c.user_id = u.id
        JOIN place p ON c.place_id = p.id
        WHERE c.id = $complaint_id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("<div class='container mt-5 text-center text-danger'><h4>Query failed: " . mysqli_error($conn) . "</h4></div>");
}

if (mysqli_num_rows($result) == 0) {
    echo "<div class='container mt-5 text-center text-danger'><h4>Complaint not found.</h4></div>";
    exit;
}

$complaint = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Complaint</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/css/admin.css">
    <style>
        .main-container{
            margin-top: 5%;
            margin-left: 15vw;
            margin-bottom: 3%;
        }
    </style>
</head>
<body>
<?php include './nav.php';?>

<div class="container d-flex flex-column align-items-center justify-content-center main-container">
    <h2 class="mb-4 text-center">Complaint Details</h2>
    
    <div class="card p-4 w-100 shadow-sm" style="max-width: 700px;">
    <div class="row m-1">
        <div class="col-4 fw-bold">Complaint ID:</div>
        <div class="col-8"><?= $complaint['id'] ?></div>
    </div>
    <hr class="my-1">
    <div class="row m-1">
        <div class="col-4 fw-bold">User:</div>
        <div class="col-8"><?= $complaint['user_name'] ?></div>
    </div>
    <hr class="my-1">
    <div class="row m-1">
        <div class="col-4 fw-bold">Place:</div>
        <div class="col-8"><?= $complaint['place_name'] ?></div>
    </div>
    <hr class="my-1">
    <div class="row m-1">
        <div class="col-4 fw-bold">Title:</div>
        <div class="col-8"><?= $complaint['title'] ?></div>
    </div>
    <hr class="my-2">
    <div class="row m-1">
        <div class="col-4 fw-bold">Description:</div>
        <div class="col-8"><?= $complaint['description'] ?></div>
    </div>
    <hr class="my-2">
    <div class="row m-1">
        <div class="col-4 fw-bold">Status:</div>
        <div class="col-8">
            <span class="badge bg-<?= $complaint['complaint_status'] == 'Resolved' ? 'success' : ($complaint['complaint_status'] == 'In-Progress' ? 'warning text-dark' : 'secondary') ?>">
                <?= ucfirst($complaint['complaint_status']) ?>
            </span>
        </div>
        
    </div>
    <hr class="my-1">
    <div class="row m-1">
        <div class="col-4 fw-bold">Date:</div>
        <div class="col-8"><?= date('d M Y, h:i A', strtotime($complaint['created_at'])) ?></div>
    </div>

    <?php if (!empty($complaint['photo'])): ?>
    <hr class="my-1">
    <div class="text-center mt-3">
        <img src="../<?= htmlspecialchars($complaint['photo']) ?>" alt="Complaint Image" class="img-fluid rounded" style="max-height: 300px;">
    </div>
    <?php endif; ?>
</div>



    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-success"><i class="bi bi-arrow-left"></i> Back to Complaints</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
