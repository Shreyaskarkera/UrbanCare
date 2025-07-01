<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../authentication.php';

$conn = db_connect();
if (!$conn) {
    die("Database connection failed.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone_no = trim($_POST['phone']);

    if (empty($name) || empty($email) || empty($password) || empty($phone_no)) {
        die("All fields are required.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("This email is already registered.");
    }
    $stmt->close();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone_no, role_id) VALUES (?, ?, ?, ?, ?)");
    $role_id = 1;
    $stmt->bind_param("ssssi", $name, $email, $hashedPassword, $phone_no, $role_id);
    
    
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        die("Error inserting data: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
