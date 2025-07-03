<?php
session_start();
include '../connection.php';
$conn = db_connect();

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

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone_no']);

    $photo = $supervisor['photo']; // keep current photo by default

    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../uploads/";
        $newPhotoName = time() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $newPhotoName;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_extensions) && $_FILES["photo"]["size"] <= 2000000) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $newPhotoName;
            } else {
                $error = "Failed to upload photo.";
            }
        } else {
            $error = "Invalid file type or size exceeded 2MB.";
        }
    }

    if (empty($error)) {
        $update_sql = "UPDATE users SET name = ?, email = ?, phone_no = ?, photo = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $name, $email, $phone_no, $photo, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = true;
            header("Location: update_supervisor_profile.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #2b2f37;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 40px;
        }

        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #373c47;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 20px;
            color: #e0e0e0;
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #4a6fa5;
            display: block;
            margin: 0 auto 20px;
            background-color: #2b2f37;
        }

        .form-control {
            background-color: #1e2128;
            border: 1px solid #444;
            color: #e0e0e0;
            margin-bottom: 15px;
        }

        .form-control:focus {
            background-color: #1e2128;
            color: #e0e0e0;
            border-color: #4a6fa5;
            box-shadow: 0 0 0 0.25rem rgba(74, 111, 165, 0.25);
        }

        .btn-primary {
            background-color: #4a6fa5;
            border: none;
            padding: 10px;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #3a5a8c;
        }

        .alert {
            margin-bottom: 20px;
        }

        .file-input-label {
            display: block;
            margin-bottom: 20px;
            cursor: pointer;
            color: #6c9ce8;
            text-align: center;
        }

        .file-input-label:hover {
            color: #8ab4ff;
            text-decoration: underline;
        }

        #fileInput {
            display: none;
        }

        #successToast {
            display: none;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <div class="profile-header">
            <h3><i class="fas fa-user-edit me-2"></i>Update Profile</h3>
        </div>

        <?php
            $uploadPath = '../uploads/' . $supervisor['photo'];
<<<<<<< HEAD
            $photoSrc = (!empty($supervisor['photo']) && file_exists($uploadPath)) ? $uploadPath : '../default_profile_picture.jpg';
=======
            $photoSrc = (!empty($supervisor['photo']) && file_exists($uploadPath)) ? $uploadPath : '../asset/images/default_profile.png';
>>>>>>> 963cf97d0c76debcafe1ed9557be3be99da14b2d
        ?>

        <img id="profilePreview" src="<?= htmlspecialchars($photoSrc) ?>" class="profile-pic" alt="Profile Picture">

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="update_supervisor_profile.php" method="post" enctype="multipart/form-data">
            <label for="fileInput" class="file-input-label">
                <i class="fas fa-camera me-2"></i>Change Profile Picture
            </label>
            <input type="file" id="fileInput" name="photo" accept="image/*" onchange="previewImage(event)">
            
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($supervisor['name']) ?>" required placeholder="Full Name">
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($supervisor['email']) ?>" required placeholder="Email Address">
            <input type="text" name="phone_no" class="form-control" value="<?= htmlspecialchars($supervisor['phone_no']) ?>" required placeholder="Phone Number">
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-save me-2"></i>Update Profile
            </button>
        </form>
    </div>

    <!-- Success Toast -->
    <div id="successToast">Profile updated successfully!</div>

    <?php if (!empty($_SESSION['success'])): ?>
        <script>
            document.getElementById("successToast").style.display = "block";
            setTimeout(() => {
                document.getElementById("successToast").style.display = "none";
                window.location.href = "index.php";
            }, 2000);
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('profilePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
