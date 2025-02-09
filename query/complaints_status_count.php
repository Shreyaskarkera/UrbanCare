<?php
// Connect to database
include '../connection.php'; 

$conn = db_connect();

$supervisor_id = isset($_GET['supervisor_id']) ? intval($_GET['supervisor_id']) : 0;

// Fetch count of each status
$sql = "SELECT complaint_status, COUNT(*) as count FROM complaints WHERE supervisor_id = '$supervisor_id' GROUP BY complaint_status";
$result = $conn->query($sql);

$statusCounts = ["Open" => 0, "In-Progress" => 0, "Resolved" => 0, "Rejected" => 0];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statusCounts[$row["complaint_status"]] = $row["count"];
    }
}

db_close($conn);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($statusCounts);
?>
