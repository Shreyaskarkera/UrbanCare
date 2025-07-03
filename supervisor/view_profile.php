<?php
session_start();
include '../connection.php';
$conn = db_connect();

// Check if supervisor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2 || $_SESSION['role_name'] != 'SUPERVISOR') {
    echo "<script>
    alert('You do not have access to this page.'); window.location.href = '../login.php';
    </script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Fetch supervisor details
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

<<<<<<< HEAD
// Image handling (uses ../uploads/ + filename from DB)
=======

>>>>>>> 963cf97d0c76debcafe1ed9557be3be99da14b2d
$defaultImage = '../asset/images/default_profile.png';
$photoFilename = $user['photo'];
$photoWebPath = "../uploads/" . $photoFilename;
$photoServerPath = __DIR__ . "/../uploads/" . $photoFilename;
$finalPhoto = (!empty($photoFilename) && file_exists($photoServerPath)) ? $photoWebPath : $defaultImage;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #2b2f37;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 30px;
        }

        .profile-card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #373c47;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .profile-header {
            background-color: #1e2128;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #444;
            position: relative;
        }

        .profile-header h3 {
            margin: 0;
            font-size: 1.2rem;
            display: inline-flex;
            align-items: center;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #444;
            object-fit: cover;
            margin: 50px auto 15px;
            display: block;
            background-color: #2b2f37;
        }

        .profile-body {
            padding: 20px;
            padding-top: 10px;
        }

        .detail-row {
            padding: 10px 0;
            border-bottom: 1px solid #444;
            font-size: 0.9rem;
        }

        .detail-label {
            font-weight: 600;
            color: #a0a0a0;
        }

        .btn-edit {
            background-color: #4a6fa5;
            border: none;
            padding: 6px 20px;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        a {
            color: #6c9ce8;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <h3><i class="fas fa-user-tie me-2"></i>Supervisor Profile</h3>
            </div>

            <img src="<?= htmlspecialchars($finalPhoto) ?>" class="profile-img" alt="Profile Picture">

            <div class="profile-body">
                <h5 class="text-center mb-3"><?php echo htmlspecialchars($user['name']); ?></h5>

                <div class="detail-row">
                    <div class="row">
                        <div class="col-md-3 detail-label">ID</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($user['id']); ?></div>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="row">
                        <div class="col-md-3 detail-label">Name</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($user['name']); ?></div>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="row">
                        <div class="col-md-3 detail-label">Email</div>
                        <div class="col-md-9">
                            <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                                <?php echo htmlspecialchars($user['email']); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="row">
                        <div class="col-md-3 detail-label">Phone</div>
                        <div class="col-md-9">
                            <a href="tel:<?php echo htmlspecialchars($user['phone_no']); ?>">
                                <?php echo htmlspecialchars($user['phone_no']); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="update_supervisor_profile.php" class="btn btn-edit">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
