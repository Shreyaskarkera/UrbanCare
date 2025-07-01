<?php
require '../connection.php';

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=Admin_Report.csv");
header("Pragma: no-cache");
header("Expires: 0");

$conn = db_connect();

// Get filters from GET request with validation
$from_date = isset($_GET['start']) ? $_GET['start'] : null;
$to_date = isset($_GET['end']) ? $_GET['end'] : null;
$location_id = isset($_GET['location']) ? (int)$_GET['location'] : null;
$supervisor_id = isset($_GET['supervisor_id']) ? (int)$_GET['supervisor_id'] : null;

// Initialize the base query
$query = "SELECT `id`, `title`, `user_id`, `complaint_type_id`, `place_id`, `photo`, 
                 `location`, `latitude`, `longitude`, `description`, `complaint_status`, 
                 `supervisor_id`, `action_date`, `resolved_date`, `created_at`, `updated_at`
          FROM `complaints`
          WHERE 1=1";

$params = [];
$types = "";

// Date filter
if ($from_date && $to_date) {
    $query .= " AND DATE(`created_at`) BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
    $types .= "ss";
}

// Supervisor filter
if (!empty($supervisor_id)) {
    $query .= " AND `supervisor_id` = ?";
    $params[] = $supervisor_id;
    $types .= "i";
}

// Location filter
if (!empty($location_id) && $location_id != 0) {
    $query .= " AND `place_id` = ?";
    $params[] = $location_id;
    $types .= "i";
}

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Check if any records exist
if ($result->num_rows === 0) {
    echo "No records found.";
    exit;
}

// UTF-8 BOM for Excel compatibility
echo "\xEF\xBB\xBF";

// CSV Header
echo '"ID","Title","User ID","Supervisor ID","Complaint Type","Place ID","Photo","Location","Latitude","Longitude","Description","Status","Action Date","Resolved Date","Created At","Updated At"' . "\n";

// Output data rows
while ($row = $result->fetch_assoc()) {
    echo '"' . implode('","', array_map(fn($v) => str_replace('"', '""', $v), [
        $row['id'], $row['title'], $row['user_id'], $row['supervisor_id'], 
        $row['complaint_type_id'], $row['place_id'], $row['photo'], 
        $row['location'], $row['latitude'], $row['longitude'], $row['description'], 
        $row['complaint_status'], $row['action_date'], $row['resolved_date'], 
        $row['created_at'], $row['updated_at']
    ])) . "\"\n";
}
exit;
?>
