<?php
session_start();
include '../connection.php'; // Database connection
$conn = db_connect();

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch supervisor details
$sql = "SELECT name, email, phone_no, photo FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$supervisor = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone_no']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    
    // Handle profile photo upload
    $photo = $supervisor['photo']; // Keep old photo by default
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../uploads/";
        $photo = time() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $photo;
        
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowed_extensions)) {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES["photo"]["size"] > 2000000) { // Limit file size to 2MB
            $error = "File size must be under 2MB.";
        } else {
            move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
        }
    }

    // Update Query
    if (!isset($error)) {
        $update_sql = "UPDATE users SET name = ?, email = ?, phone_no = ?, photo = ?" . ($password ? ", password = ?" : "") . " WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        
        if ($password) {
            $stmt->bind_param("sssssi", $name, $email, $phone_no, $photo, $password, $user_id);
        } else {
            $stmt->bind_param("ssssi", $name, $email, $phone_no, $photo, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: update_supervisor.php");
            exit();
        } else {
            $error = "Error updating profile. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Supervisor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(255, 255, 255);
            color: black;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
            background-color:rgb(235, 235, 235);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 3px solid #007bff;
        }
        .form-container {
            flex: 1;
        }
        .btn {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Update Profile</h3>
        
        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row align-items-center">
            <!-- Profile Picture (Left Side) -->
            <div class="col-md-4 text-center">
                <?php if ($supervisor['photo']): ?>
                    <img id="profilePreview" src="../uploads/<?= htmlspecialchars($supervisor['photo']) ?>" class="profile-pic">
                <?php else: ?>
                    <img id="profilePreview" src="https://via.placeholder.com/150" class="profile-pic">
                <?php endif; ?>
                
                <input type="file" name="photo" class="form-control mt-2" accept="image/*" onchange="previewImage(event)">
            </div>

            <!-- Update Form (Right Side) -->
            <div class="col-md-8">
                <form action="update_supervisor.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($supervisor['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($supervisor['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone_no" class="form-control" value="<?= htmlspecialchars($supervisor['phone_no']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password (optional)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const image = document.getElementById('profilePreview');
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
