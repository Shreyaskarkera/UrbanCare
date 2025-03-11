<?php
require '../connection.php'; // Include DB connection

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Supervisor_Report.xls");
header("Pragma: no-cache");
header("Expires: 0");

$conn = db_connect();
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}

$supervisor_id = $_SESSION['user_id'];

// Fetch assigned place
$query = "SELECT place_id FROM supervisor_map WHERE supervisor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supervisor_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("No place assigned to this supervisor.");
}

$place_id = $row['place_id'];

// Fetch complaints
$query = "SELECT id, title, complaint_status, created_at FROM complaints WHERE place_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $place_id);
$stmt->execute();
$result = $stmt->get_result();

// Print table headers
echo "ID\tTitle\tStatus\tDate\n";

// Print table rows
while ($row = $result->fetch_assoc()) {
    echo "{$row['id']}\t{$row['title']}\t{$row['complaint_status']}\t{$row['created_at']}\n";
}

exit;
?>
