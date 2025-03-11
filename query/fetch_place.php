<?php

include '../connection.php'; 

$conn = db_connect();

// Query to fetch active complaint types
$sql = "SELECT id, name AS place_name FROM place WHERE is_active = true";
$result = $conn->query($sql);

$places = [];

if ($result->num_rows > 0) {
    // Fetch all rows and store them in an array
    while ($row = mysqli_fetch_assoc($result)) {
        $places[] = $row;
    }
}


// Close the database connection
db_close($conn);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($places);

?>