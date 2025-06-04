<?php

include '../connection.php'; 

$conn = db_connect();
$user_id = $_GET['user_id'];

// Query to fetch active complaint types
$sql = "SELECT id, message, created_at, complaint_id FROM notifications WHERE user_id = '$user_id' AND is_read = false ORDER BY created_at DESC";
$result = $conn->query($sql);

$notification = [];

if ($result->num_rows > 0) {
    // Fetch all rows and store them in an array
    while ($row = $result->fetch_assoc()) {
        $notification[] = $row;
    }
}


// Close the database connection
db_close($conn);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($notification);

?>
