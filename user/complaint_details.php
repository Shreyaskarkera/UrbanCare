<?php
session_start();
include '../connection.php'; // Include database connection

// Check if complaint ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid request.'); window.location.href = 'view_complaints.php';</script>";
    exit();
}


// Check if session is set and the role is USER
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    // Redirect to index page if the session does not exist or role is not USER
    header("Location: ../index.html");
    exit();
}

if ($_SESSION['role_name'] != 'USER') {
    echo "<script>
    alert('Dont have access to this page');window.location.href = '../login.php';
   </script>";
}



$name = $_SESSION['name'];



$complaint_id = $_GET['id'];
$conn = db_connect();
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch complaint details
$query = "SELECT id, title, user_id, complaint_type_id, place_id, photo, location, latitude, longitude, 
                 description, complaint_status, supervisor_id, action_date, resolved_date, created_at, updated_at 
          FROM complaints WHERE id = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("SQL Error: " . $conn->error); // Show actual error message
}

$stmt->bind_param("i", $complaint_id);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();
$stmt->close();
mysqli_close($conn);

// Redirect back if complaint not found
if (!$complaint) {
    echo "<script>alert('Complaint not found.'); window.location.href = 'view_complaints.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/custom.css">

    <style>
        :root {
            --primary-color: #0077B6;
            --secondary-color: #9e9e9e;
            --toggle-icon-secondary-color: #ffffff00;
            --accent-color: #FF6F61;
            --background-color: #F1F1F1;
            --text-color: #333333;
            --footer-color: #2A3D66;
        }

        .main-container {
            margin-top: 100px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .card {
            border-radius: 10px;
            padding: 20px;
            overflow: hidden;
        }

        .img-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        @media (max-width: 576px) {
            .main-container {
                margin-top: 70px;
                padding: 10px;
            }
        }
       .footer{
        margin-top: 10px;
       }
    </style>
</head>

<body>
    <?php include './nav.php'; ?>
    <div class="container-fluid">
        <div class="main-container">
            <h2 class="text-center mb-4">Complaint Details</h2>
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Complaint ID</th>
                                <td><?= $complaint['id'] ?></td>
                            </tr>
                            <tr>
                                <th>Title</th>
                                <td><?= htmlspecialchars($complaint['title']) ?></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td><?= nl2br(htmlspecialchars($complaint['description'])) ?></td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td><?= htmlspecialchars($complaint['location']) ?></td>
                            </tr>
                            <tr>
                                <th>Latitude</th>
                                <td><?= $complaint['latitude'] ?></td>
                            </tr>
                            <tr>
                                <th>Longitude</th>
                                <td><?= $complaint['longitude'] ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>

                                <?php
                                $status = $complaint["complaint_status"];

                                $statusClasses = [
                                    'Open' => 'badge bg-primary',
                                    'In-Progress' => 'badge bg-warning text-dark',
                                    'Resolved' => 'badge bg-success',
                                    'Rejected' => 'badge bg-danger'
                                ];

                                echo '<td><span class="' . ($statusClasses[$status] ?? 'badge bg-secondary') . '" style="font-size: 18px; padding: 8px 12px;">' . htmlspecialchars($status) . '</span></td>';
                                ?>

                            </tr>
                            <tr>
                                <th>Action Date</th>
                                <td><?= $complaint['action_date'] ? $complaint['action_date'] : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <th>Resolved Date</th>
                                <td><?= $complaint['resolved_date'] ? $complaint['resolved_date'] : 'N/A' ?></td>
                            </tr>
                            <?php if (!empty($complaint['photo'])): ?>
                                <tr>
                                    <th>Complaint Image</th>
                                    <td class="text-center">
                                        <img src="../<?= htmlspecialchars($complaint['photo']) ?>" alt="Complaint Image" class="img-fluid rounded">
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <a href="view_complaints.php" class="btn btn-primary mt-3">Back to Complaints</a>
                </div>
            </div>
        </div>
    </div>
    <?php include './footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  
</body>

</html>