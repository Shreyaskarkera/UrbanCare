<?php
session_start();


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


?>


<?php
include '../connection.php'; // Database connection
require_once '../image_upload.php';


$conn=db_connect();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve form data
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone_no = trim($_POST['phone_no']);
$image_url=null;

// Handle profile picture upload
if (!empty($_FILES['profile_pic']['name'])) {
    
    if (isset($_FILES["profile_pic"])) {
        $relativeDir = "uploads/".$user_id."/profile"; // Custom folder inside root
        $result = uploadImage($relativeDir, $_FILES["profile_pic"]);
        echo "<script> console.log(".json_encode($result).")</script>";
        $image_url = $result['path'];
    }

    // Update query with profile picture
    $sql = "UPDATE users SET name=?, email=?, phone_no=?, photo=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $phone_no, $image_url, $user_id);
} else {
    // Update query without profile picture
    $sql = "UPDATE users SET name=?, email=?, phone_no=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $phone_no, $user_id);
}

// Execute the query
if ($stmt->execute()) {
    // ✅ **Update Session Data After Profile Update**
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['phone_no'] = $phone_no;
    if (!empty($photo)) {
        $_SESSION['photo'] = $image_url;
    }

    header("Location: index.php?success=Profile Updated");
} else {
    echo "Error updating profile.";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
