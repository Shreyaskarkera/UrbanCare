<?php
include '../connection.php';
$conn = db_connect();
session_start(); // Start session to access user_id

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

// Get user input and sanitize
$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$complaint_id = mysqli_real_escape_string($conn, $_POST['complaint_id']);
$feedback_type = mysqli_real_escape_string($conn, $_POST['feedback_type']);
$rating = mysqli_real_escape_string($conn, $_POST['rating']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);

// Insert into feedback table
$query = "INSERT INTO feedback (user_id, complaint_id, feedback_type, rating, comment, created_at) 
          VALUES ('$user_id', '$complaint_id', '$feedback_type', '$rating', '$comment', NOW())";

if (mysqli_query($conn, $query)) {
    header("Location: index.php?success=Feedback submitted successfully");
    exit();
} else {
    die("Error: " . mysqli_error($conn));
}
?>
