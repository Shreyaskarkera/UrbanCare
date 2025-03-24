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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0077B6;
            /* Soft Blue */
            --secondary-color: #9e9e9e;
            --toggle-icon-secondary-color: #ffffff00;
            /* Vibrant Green */
            --accent-color: #FF6F61;
            /* Warm Coral */
            --background-color: #F1F1F1;
            /* Soft Gray */
            --text-color: #333333;
            /* Charcoal Gray */
            --footer-color: #2A3D66;
            /* Deep Slate */
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding-top: 30px;
            margin-top: 70px;
        }

        .card {
            border-radius: 10px;
            padding: 20px;
        }

        .img-container {
            text-align: center;
        }

        .img-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .navbar {
            background-color: var(--primary-color);
        }

        .navbar-brand h1 {
            color: white;
        }

        .navbar-nav .nav-link {
            color: white;
        }

        .navbar-nav .nav-link:hover {
            color: rgb(73, 73, 73) !important;
        }

        /* Custom Toggle Icon */
        .navbar-toggler-icon {
            background-color: var(--toggle-icon-secondary-color);
            /* Change the toggle icon color */
        }

        .navbar-toggler-icon:hover {
            border: 1px solid var(--secondary-color);
            /* Optional: Add border for better visibility */
        }

        .navbar-toggler-icon:hover {
            background-color: var(--toggle-icon-secondary-color);
            /* Hover effect */
        }

        .navbar-toggler-icon:focus {
            box-shadow: none;
            /* Removes the blue shadow when focused */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <h1>Urban Care</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./complaint_raise.php">Raise Complaint</a></li>
                    <li class="nav-item"><a class="nav-link" href="./view_complaints.php">View Complaints</a></li>
                    <li class="nav-item"><a class="nav-link" href="#help">Help</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="./user_notification.php">
                            <i class="bi bi-bell"></i>
                            <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo $name; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="./view_profile.php">View Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2 class="text-center mb-4">Complaint Details</h2>
        <div class="card p-4">
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
                        <td>
                            <?php
                            $status = $complaint['complaint_status'];
                            $statusColors = [
                                'Open' => 'color: blue; font-weight: bold;',
                                'In-Progress' => 'color: orange; font-weight: bold;',
                                'Resolved' => 'color: green; font-weight: bold;',
                                'Rejected' => 'color: red; font-weight: bold;'
                            ];
                            ?>
                            <span style="<?= $statusColors[$status] ?? 'color: black; font-weight: bold;' ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </td>
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
                                <img src="../<?= htmlspecialchars($complaint['photo']) ?>" alt="Complaint Image" style="max-width: 300px; border-radius: 8px;">
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="text-center">
                <a href="view_complaints.php" class="btn btn-primary mt-3">Back to Complaints</a>
            </div>
        </div>
    </div>


</body>

</html>