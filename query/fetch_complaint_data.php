<?php
include '../connection.php'; // Ensure this connects to the database

header('Content-Type: application/json');

$conn = db_connect();

// Check if connection was successful
if (!$conn) {
    echo json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]);
    exit;
}

// Retrieve and sanitize inputs to prevent SQL injection
$location_id = isset($_POST['location_id']) ? (int)$_POST['location_id'] : 0;
$supervisor_id = isset($_POST['supervisor_id']) ? (int)$_POST['supervisor_id'] : 0;
$fromDate = isset($_POST['fromDate']) ? $_POST['fromDate'] : '';
$toDate = isset($_POST['toDate']) ? $_POST['toDate'] : '';

// Start building the query
$query = "SELECT c.id AS complaint_id, c.title, c.user_id, c.complaint_type_id, c.place_id, c.photo, c.location, c.latitude, c.longitude, c.description, c.complaint_status, 
                 c.supervisor_id, c.action_date, c.resolved_date, c.created_at, c.updated_at, 
                 p.name AS place_name, u.name AS supervisor_name
          FROM complaints c
          JOIN place p ON c.place_id = p.id
          LEFT JOIN users u ON c.supervisor_id = u.id AND u.role_id = 2  -- Join users table for supervisors (role_id = 2)
          WHERE 1=1 ";  // Keep the base condition for the query

// Add conditions for filters
if ($location_id > 0) {
    $query .= " AND c.place_id = ? ";
}

if ($supervisor_id > 0) {
    $query .= " AND c.supervisor_id = ? ";
}

// Add date range filter if specified
if (!empty($fromDate) && !empty($toDate)) {
    $query .= " AND c.created_at BETWEEN ? AND ? ";
}

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    echo json_encode(["error" => "Statement preparation failed: " . mysqli_error($conn)]);
    exit;
}

// Bind parameters based on conditions
if ($location_id > 0 && $supervisor_id > 0 && !empty($fromDate) && !empty($toDate)) {
    mysqli_stmt_bind_param($stmt, 'iiss', $location_id, $supervisor_id, $fromDate, $toDate);
} elseif ($location_id > 0 && $supervisor_id > 0) {
    mysqli_stmt_bind_param($stmt, 'ii', $location_id, $supervisor_id);
} elseif ($location_id > 0 && !empty($fromDate) && !empty($toDate)) {
    mysqli_stmt_bind_param($stmt, 'iss', $location_id, $fromDate, $toDate);
} elseif ($supervisor_id > 0 && !empty($fromDate) && !empty($toDate)) {
    mysqli_stmt_bind_param($stmt, 'iss', $supervisor_id, $fromDate, $toDate);
} elseif ($location_id > 0) {
    mysqli_stmt_bind_param($stmt, 'i', $location_id);
} elseif ($supervisor_id > 0) {
    mysqli_stmt_bind_param($stmt, 'i', $supervisor_id);
} elseif (!empty($fromDate) && !empty($toDate)) {
    mysqli_stmt_bind_param($stmt, 'ss', $fromDate, $toDate);
}

// Execute the statement
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(["error" => "Query execution failed: " . mysqli_error($conn)]);
    exit;
}

$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    echo json_encode(["error" => "Fetching result failed: " . mysqli_error($conn)]);
    exit;
}

// Prepare status counts for summary
$statusCounts = [
    "Open" => 0, 
    "In-Progress" => 0, 
    "Resolved" => 0, 
    "Rejected" => 0
];

// Array to store complaints
$complaints = [];

// Initialize the serial number counter
$serialNumber = 1;

// Initialize the serial number counter
$serialNumber = 1;

// Fetch and process each row
while ($row = $result->fetch_assoc()) {
    // Get the status of the complaint
    $status = $row["complaint_status"];

    // Increment the corresponding status count if the status exists in the array
    if (array_key_exists($status, $statusCounts)) {
        $statusCounts[$status]++;
    }

    // Add the complaint details to the complaints array with a serial number
    $complaints[] = [
        "serial_number" => $serialNumber,  // Serial number
        "complaint_id" => $row["complaint_id"],
        "description" => $row["description"],
        "location_name" => $row["place_name"],  // Get location name
        "supervisor_name" => $row["supervisor_name"],  // Get supervisor name
        "status" => $status,  // Store the status of the complaint
        "created_at" => $row["created_at"]  // Date when the complaint was created
    ];

    // Increment the serial number for the next complaint
    $serialNumber++;
}


// Return status counts and complaints in the response
echo json_encode([
    "statusCounts" => $statusCounts,  // Return the status counts
    "complaints" => $complaints      // Return the complaints data
]);

mysqli_close($conn);  // Close the database connection
?>
