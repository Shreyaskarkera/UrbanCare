<?php
require '../connection.php';

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=Supervisor_Report.csv");
header("Pragma: no-cache");
header("Expires: 0");

$conn = db_connect();
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}

$supervisor_id = $_SESSION['user_id'];

$from_date = $_GET['start'] ?? null;
$to_date = $_GET['end'] ?? null;

if ($from_date && $to_date) {
    $query = "SELECT `id`, `title`, `user_id`, `complaint_type_id`, `place_id`, `photo`, 
                     `location`, `latitude`, `longitude`, `description`, `complaint_status`, 
                     `supervisor_id`, `action_date`, `resolved_date`, `created_at`, `updated_at`
              FROM `complaints`
              WHERE DATE(`created_at`) BETWEEN ? AND ? AND supervisor_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $from_date, $to_date, $supervisor_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Please provide valid 'from_date' and 'to_date'.";
    exit;
}

// UTF-8 BOM to fix encoding in Excel
echo "\xEF\xBB\xBF";

// Table headers (CSV format uses commas to separate fields)
echo '"ID","Title","User ID","Complaint Type","Place ID","Photo","Location","Latitude","Longitude","Description","Status","Action Date","Resolved Date","Created At","Updated At"' . "\n";

// Table rows (ensure proper comma separation, and escape any field with commas or special characters)
while ($row = $result->fetch_assoc()) {
    // Print each value in its own cell and ensure each value is correctly wrapped with double quotes to handle commas and special characters.
    echo '"' . str_replace('"', '""', $row['id']) . '",';
    echo '"' . str_replace('"', '""', $row['title']) . '",';
    echo '"' . str_replace('"', '""', $row['user_id']) . '",';
    echo '"' . str_replace('"', '""', $row['complaint_type_id']) . '",';
    echo '"' . str_replace('"', '""', $row['place_id']) . '",';
    echo '"' . str_replace('"', '""', $row['photo']) . '",';
    echo '"' . str_replace('"', '""', $row['location']) . '",';
    echo '"' . str_replace('"', '""', $row['latitude']) . '",';
    echo '"' . str_replace('"', '""', $row['longitude']) . '",';
    echo '"' . str_replace('"', '""', $row['description']) . '",';
    echo '"' . str_replace('"', '""', $row['complaint_status']) . '",';
    echo '"' . str_replace('"', '""', $row['action_date']) . '",';
    echo '"' . str_replace('"', '""', $row['resolved_date']) . '",';
    echo '"' . str_replace('"', '""', $row['created_at']) . '",';
    echo '"' . str_replace('"', '""', $row['updated_at']) . '"' . "\n"; // Ensure the newline here
}

exit;
