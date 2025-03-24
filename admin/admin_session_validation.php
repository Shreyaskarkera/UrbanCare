<?php
session_start();

// Check session and admin role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_name'] != 'ADMIN') {
    echo "<script>alert('You don’t have access to this page'); window.location.href = '../login.php';</script>";
    exit();
}

$name = $_SESSION['name'];
?>