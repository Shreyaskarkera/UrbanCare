<?php
session_start();
include '../connection.php'; // Ensure this file connects to the database

$conn = db_connect();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name=$_SESSION['name'];// Fetch user details from the database
$sql = "SELECT name, email, phone_no, photo FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Default profile picture if none is found
$photo = !empty($user['photo']) ? "../" . htmlspecialchars($user['photo']) : "../asset/images/default_profile.png";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/custom.css">

    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .main-container {
            margin-top: 100px; /* Adjust as needed */
            max-width: 600px; /* Limit width */
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 100px;
        }

        /* Responsive styles for smaller screens */
        @media (max-width: 576px) {
            .profile-img {
                width: 100px;
                height: 100px;
            }
            .main-container {
                margin-top: 100px;
                padding: 10px;
            }
            .form-label, .btn {
                font-size: 14px;
            }
            .card {
                padding: 15px;
                /* margin-bottom: 20%; */
            }
        }
       
    </style>
</head>
<body>
    <?php include './nav.php'; ?>
    <div class="container main-container">
        <h2 class="text-center mb-4">Update Profile</h2>
        <div class="card p-4 shadow">
            <form action="update_profile_action.php" method="post" enctype="multipart/form-data">
                <div class="text-center">
                    <img src="<?= $photo ?>" class="profile-img img-thumbnail mb-3" alt="Profile Picture">
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone_no" class="form-control" value="<?= htmlspecialchars($user['phone_no']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>
        </div>
    </div>

  
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  
    <?php include './footer.php'; ?>

</body>
</html>
