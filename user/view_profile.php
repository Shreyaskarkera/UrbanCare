<?php
session_start();

include('../query/fetch_users.php');



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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-card {
            
            margin-top: 50px; /* Adds space from the top */
        }
        .profile-img {
            width: 100px; /* Makes the image slightly bigger */
            height: 100px; /* Maintains aspect ratio */
            border-radius: 100%;        
        }
        alt{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card profile-card p-4 shadow-md">
            <div class="headding">
                <h2 class="card-title mb-4 text-center"><?php  echo  htmlspecialchars($user['name']); ?></h2>
            </div>
            <img src="<?php echo "../".$user['photo']; ?>" class="img-thumbnail profile-img mb-3 mx-auto d-block" alt="">

            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_no']); ?></p>
            <a href="./update_profile.php" class="btn btn-primary btn-block">Edit Profile</a>
        </div>
    </div>

    <!-- Bootstrap JS (optional, for modal, dropdowns, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

