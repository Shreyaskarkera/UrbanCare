<?php
session_start();
include('../query/fetch_users.php');

// Check if session is set and the role is USER
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header("Location: ../index.html");
    exit();
}

if ($_SESSION['role_name'] != 'USER') {
    echo "<script>
    alert('Dont have access to this page');
    window.location.href = '../login.php';
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
            max-width: 1000px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            background: #fff;
            flex-wrap: wrap;
            align-items: center;
        }
        .profile-img {
            width: 250px;
            height: 250px;
            border-radius: 15px;
            object-fit: cover;
            margin-right: 40px;
        }
        .profile-details {
            flex: 1;
            font-size: 1.2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .profile-details p {
            margin: 10px 0;
            font-size: 1.1rem;
        }
        .btn-primary {
            font-size: 1.2rem;
            padding: 10px 20px;
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            .profile-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 30px;
            }
            .profile-img {
                margin: 0 0 20px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <div class="card profile-card">
            <img src="<?php echo "../".$user['photo']; ?>" class="img-thumbnail profile-img" alt="Profile Picture">
            <div class="profile-details">
                <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_no']); ?></p>
                <p><strong>Password:</strong> *********</p>
                <a href="./update_profile.php" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>