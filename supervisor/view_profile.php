<?php
session_start();
include '../connection.php'; // Ensure this file connects to the database
$conn = db_connect();

// Check if supervisor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SESSION['role_name'] != 'SUPERVISOR') {
    echo "<script>
    alert('Dont have access to this page');window.location.href = '../login.php';
   </script>";
}

$name = $_SESSION['name'];


// Fetch supervisor details from the database
$sql = "SELECT id, name, email, phone_no, photo FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

// Default profile image
$profile_image = !empty($user['photo']) ? "../" . $user['photo'] : '../default_profile_picture.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
     .background {
    background-color: rgba(75, 75, 75, 0.91);
    width: 100%;
    min-height: 100vh; /* Ensures full height */
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.navbar {
    background-color: #3c4a59; /* Dark grey with blue undertones */
}

.navbar-toggler-icon {
    background-color: #3c4a59;
}

.navbar-nav .nav-link,
.navbar-brand {
    color: #f5f5f5;
}

.navbar-nav .nav-link:hover,
.navbar-brand:hover {
    color: #3498db;
}

.profile-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
    background-color: #f8f9fa;
}

.profile-card {
    display: flex;
    flex-direction: row;
    width: 90%;
    max-width: 800px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    background: #fff;
    flex-wrap: wrap;
    align-items: center;
    gap: 20px;
}

.profile-img-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.profile-img {
    width: 220px;
    height: 220px;
    border-radius: 15px;
    object-fit: cover;
    background-color: #d8d8d8;
    border: 3px solid #3c4a59;
}

.profile-details-container {
    flex: 2;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.profile-details table {
    width: 100%;
}

.profile-details th {
    text-align: left;
    padding: 10px;
    font-size: 1rem;
    color: #3c4a59;
}

.profile-details td {
    padding: 10px;
    font-size: 1rem;
}

.btn-primary {
    font-size: 1rem;
    padding: 8px 16px;
    margin-top: 10px;
}

@media (max-width: 768px) {
    .profile-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px;
    }

    .profile-img {
        width: 180px;
        height: 180px;
    }

    .profile-details table {
        font-size: 0.9rem;
    }
}

.footer {
    text-align: center;
    padding: 15px;
    background-color: #3c4a59;
    color: white;
}

    </style>
</head>
<body>

  <!-- Top Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Urban Care</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php"><i
                                class="fas fa-home me-2"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./supervisor_report.php"><i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="supervisor_map.html"><i class="fas fa-map-marker-alt me-2"></i>Map</a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Preferences</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li> -->
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="supervisor_notification.html">
                            <i class="fas fa-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>Notification
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i><?php echo $name ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="./view_profile.php" data-bs-toggle="modal"
                                    data-bs-target="">View Profile</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#updateProfileModal">Update Profile</a></li>
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

        <div class="container profile-container ">
            <div class="background">

                <div class="card profile-card">
                    <div class="profile-img-container">
                        <img src="<?php echo htmlspecialchars($profile_image); ?>" class="img-thumbnail profile-img" alt="Profile Picture">
                    </div>
                    <div class="profile-details-container">
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td><?php echo htmlspecialchars($user['phone_no']); ?></td>
                            </tr>
                        </table>
                        <a href="./update_profile.php" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>

    <footer class="footer">

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>