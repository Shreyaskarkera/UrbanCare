<?php
session_start();

// ✅ Get current page URL
$current_url = $_SERVER['REQUEST_URI'];

// ✅ Save redirect_back if:
// - Not logged in OR
// - Logged in as ADMIN and accessing an admin page
if (
    !isset($_SESSION['role_name']) ||
    (strpos($current_url, '/admin') !== false && $_SESSION['role_name'] === 'ADMIN')
) {
    $_SESSION['redirect_back'] = $current_url;
}

// ✅ If session is not set, redirect to login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header("Location: ../login.php");
    exit();
}

// ✅ If role name not set yet, get from DB
if (!isset($_SESSION['role_name'])) {
    require_once '../connection.php';
    require_once '../authentication.php';
    
    $conn = db_connect();
    $role = getRoleById($_SESSION['role_id']);
    db_close($conn);

    if ($role) {
        $_SESSION['role_name'] = strtoupper($role['name']);
    } else {
        echo "<script>alert('Role not found.'); window.location.href='../login.php';</script>";
        exit();
    }
}

// ✅ Allow only ADMIN
if ($_SESSION['role_name'] !== 'ADMIN') {
    $redirect = '../' . strtolower($_SESSION['role_name']) . '/';
    echo "<script>
        alert('You do not have access to this page.');
        window.location.href = '$redirect';
    </script>";
    exit();
}

// ✅ Authorized admin
$name = $_SESSION['name'];
?>
