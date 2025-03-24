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
$from_date = $_GET['start'] ?? null;
$to_date = $_GET['end'] ?? null;

$result = null;
if ($from_date && $to_date) {
    $query = "SELECT `id`, `title`, `user_id`, `complaint_type_id`, `place_id`, `photo`, 
    `location`, `latitude`, `longitude`, `description`, `complaint_status`, 
    `supervisor_id`, `action_date`, `resolved_date`, `created_at`, `updated_at`
FROM `complaints`
WHERE DATE(`created_at`) BETWEEN ? AND ?";


    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $from_date, $to_date);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Please provide valid 'from_date' and 'to_date'.";
}


// Print table headers
echo "ID\tTitle\tStatus\tDate\n";

// Print table rows
while ($row = $result->fetch_assoc()) {
    echo "{$row['id']}\t{$row['title']}\t{$row['complaint_status']}\t{$row['created_at']}\n";
}


exit;
