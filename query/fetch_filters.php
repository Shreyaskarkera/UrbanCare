<?php
include '../connection.php';

header('Content-Type: application/json');

$conn = db_connect();

// Fetch locations
$locationQuery = "SELECT id, name FROM place WHERE is_active = true"; // Adjust the query as per your database schema
$locationResult = mysqli_query($conn, $locationQuery);
$locations = [];
if ($locationResult) {
    while ($row = mysqli_fetch_assoc($locationResult)) {
        $locations[] = $row; // Collect location data
    }
}

// Fetch supervisors
$supervisorQuery = "SELECT id, name FROM users WHERE role_id = 2"; // Ensure 'role_id = 2' is for supervisors
$supervisorResult = mysqli_query($conn, $supervisorQuery);
$supervisors = [];
if ($supervisorResult) {
    while ($row = mysqli_fetch_assoc($supervisorResult)) {
        $supervisors[] = $row; // Collect supervisor data
    }
}

// Send the data as JSON
echo json_encode(['locations' => $locations, 'supervisors' => $supervisors]);
?>
