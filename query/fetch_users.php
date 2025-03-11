<?php

include '../connection.php';  // Ensure this path is correct for your project


$conn=db_connect();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Get logged-in user ID

// Fetch user details from database
$sql = "SELECT id,name, email, phone_no, photo FROM users WHERE id=?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  // Bind the user ID parameter
$stmt->execute();  // Execute the query
$result = $stmt->get_result();  // Get the result of the query
$user = $result->fetch_assoc();  // Fetch the user details

// Default profile picture if not set
// $photo = !empty($user['photo']) ? $user['photo'] : 'default.jpg';

// Close the statement and connection
$stmt->close();
$conn->close();
?>
