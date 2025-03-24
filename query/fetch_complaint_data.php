<?php
include '../connection.php'; // Ensure this connects to the database

header('Content-Type: application/json');

$conn=db_connect();

$location_id = isset($_POST['location_id']) ? $_POST['location_id'] : '';
$supervisor_id = isset($_POST['supervisor_id']) ? $_POST['supervisor_id'] : '';

// Debugging: Print received POST data
error_log("Received location: " . $location_id);
error_log("Received supervisor_id: " . $supervisor_id);

$query = "SELECT c.complaint_status, COUNT(*) as count 
          FROM complaints c  WHERE 1=1 ";

if (!empty($location_id) && $location_id != 0) {
    $query .= " AND c.place_id = '$location_id' ";
}

if (!empty($supervisor_id) && $supervisor_id != 0 ) {
    $query .= " AND c.supervisor_id = '$supervisor_id' ";
}

$query .= " GROUP BY c.complaint_status";

// Debugging: Print SQL query
error_log("Executing query: " . $query);

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["error" => "Database error: " . mysqli_error($conn)]);
    exit;
}

// Default values for statuses
$statusCounts = ["Open" => 0, "In-Progress" => 0, "Resolved" => 0, "Rejected" => 0];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statusCounts[$row["complaint_status"]] = $row["count"];
    }
}

// Debugging: Print output data
error_log("Returning data: " . json_encode($statusCounts));

echo json_encode($statusCounts);
?>
