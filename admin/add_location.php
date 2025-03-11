<?php
include '../connection.php'; // Include database connection

$conn = db_connect();
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $status = $_POST['status'];

    // Validate input
    if (empty($name)) {
        echo "<script>alert('Location name cannot be empty!'); window.location.href='location.php';</script>";
        exit();
    }

    // Insert into database
    $query = "INSERT INTO place (name, is_active) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $name, $status);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Location added successfully!'); window.location.href='location.php';</script>";
    } else {
        echo "<script>alert('Error adding location!'); window.location.href='location.php';</script>";
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
