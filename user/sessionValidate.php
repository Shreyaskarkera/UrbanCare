<?php
ob_start();
session_start();
require_once '../connection.php';
require_once '../authentication.php';

$conn = db_connect();

$current_url = $_SERVER['REQUEST_URI'];

// ✅ Save redirect_back only if visiting from outside OR unauthorized role
if (
    !isset($_SESSION['role_name']) ||
    (strpos($current_url, '/user') !== false && $_SESSION['role_name'] === 'USER')
) {
    $_SESSION['redirect_back'] = $current_url;
}

// ✅ Auto-redirect to login if no session
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header("Location: ../login.php");
    exit();
}

// ✅ Get role name if not already set in session
if (!isset($_SESSION['role_name'])) {
    $role = getRoleById($_SESSION['role_id']);
    if ($role) {
        $_SESSION['role_name'] = strtoupper($role['name']);
    } else {
        echo "<script>alert('Unable to determine role.'); window.location.href = '../login.php';</script>";
        exit();
    }
}

// ✅ Allow only USER role
if ($_SESSION['role_name'] !== 'USER') {
    $role_redirect = strtolower($_SESSION['role_name']) . "/";
    echo "<script>
        alert('You do not have access to this page.');
        window.location.href = '../$role_redirect';
    </script>";
    exit();
}

// ✅ Logged-in USER access
$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];
?>
