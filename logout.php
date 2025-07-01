<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = array(); // Clear the session array

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ./index.php"); // Adjust the path if needed
exit();
?>
