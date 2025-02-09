<?php
    require_once __DIR__ . '/../authentication.php';

if (isset($_POST['signup'])) {
    // Get form data and sanitize
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone_no=$_POST['phone'];

    // Validate input (Basic Validation)
    if (empty($name) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    if (insertUser($name, $email, $password, $phone_no)) {
        echo "Registration successful!";
        header("Location: ../login.php");
    } else {
        echo "Error: ";
    }
}
?>
