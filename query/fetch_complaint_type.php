<?php

include '../connection.php'; 

$conn = db_connect();

// Query to fetch active complaint types
$sql = "SELECT id, name AS complaint_name FROM complaint_type WHERE is_active = true";
$result = $conn->query($sql);

$complaints = [];

if ($result->num_rows > 0) {
    // Fetch all rows and store them in an array
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}

// Close the database connection
db_close($conn);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($complaints);

?>

