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
    alert('You do not have access to this page');
    window.location.href = '../login.php';
    </script>";
    exit();
}

$name = $_SESSION['name'];

// Ensure $user data is available
if (!isset($user) || empty($user)) {
    echo "<script>alert('User data not found.'); window.location.href = '../login.php';</script>";
    exit();
}

// Default profile picture if none exists
$photo = !empty($user['photo']) ? "../" . htmlspecialchars($user['photo']) : "default_profile_picture.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/custom.css">
  <style>
    body {
      background: #f5f5f5;
      color: #333;
      /* font-family: 'Arial', sans-serif; */
    }

    .profile-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
      padding: 20px;
    }

    .profile-card {
      margin-top:5%;
      width: 100%;
      max-width: 450px;
      background: white;
      border-radius: 15px;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
      padding: 25px;
      text-align: center;
    }

    .profile-img-container {
      display: flex;
      justify-content: center;
      margin-bottom: 15px;
    }

    .profile-img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #007bff;
    }

    .profile-info-box {
      background: #f8f9fa;
      color: #333;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
      border-left: 4px solid #007bff;
    }

    .btn-primary {
      padding: 8px 16px;
      background: #007bff;
      border: none;
    }

    .btn-primary:hover {
      background: #0056b3;
    }

    @media (max-width: 768px) {
      .profile-card {
        max-width: 90%;
        padding: 20px;
      }
      .profile-img {
        width: 100px;
        height: 100px;
      }
    }

    @media (max-width: 480px) {
      .profile-card {
        max-width: 95%;
        padding: 15px;
      }
      .profile-img {
        width: 90px;
        height: 90px;
      }
      .profile-info-box {
        font-size: 0.9rem;
      }
    }
  
  </style>
</head>
<body>
<?php include './nav.php'; ?>
<div class="profile-container">
  <div class="profile-card">
    <div class="profile-img-container">
    <?php
  // fallback logic
  $defaultImage = '../asset/images/default_profile.png';
  $finalPhoto = (!empty($photo) && file_exists($photo)) ? $photo : $defaultImage;
?>
<img src="<?= htmlspecialchars($finalPhoto) ?>" class="profile-img" alt="Profile Picture">


    </div>
    <h2><?= htmlspecialchars($user['name']); ?></h2>
    <div class="profile-info-box"><strong>User ID:</strong> <?= htmlspecialchars($user['id']); ?></div>
    <div class="profile-info-box"><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></div>
    <div class="profile-info-box"><strong>Phone:</strong> <?= htmlspecialchars($user['phone_no']); ?></div>
    <a href="./update_profile.php" class="btn btn-primary">Edit Profile</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php include './footer.php'; ?>

</body>
</html>