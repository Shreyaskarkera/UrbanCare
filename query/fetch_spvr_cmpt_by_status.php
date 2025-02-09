<?php

include '../connection.php';

session_start();

$conn = db_connect();

$supervisor_id = $_SESSION['user_id'];
$status = $_POST['status'];

$sql = null;
$stmt = null;

if ($status != "All") {
    $sql = "SELECT c.id, ct.name as complaint_type , c.title, c.description, c.created_at, c.complaint_status, c.latitude, c.longitude, c.user_id FROM complaints c JOIN complaint_type ct ON c.complaint_type_id = ct.id WHERE c.supervisor_id = ? AND c.complaint_status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $supervisor_id, $status);
} else {
    $sql = "SELECT  c.id, ct.name as complaint_type , c.title, c.description, c.created_at, c.complaint_status, c.latitude, c.longitude FROM complaints c JOIN complaint_type ct ON c.complaint_type_id = ct.id WHERE c.supervisor_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $supervisor_id);
}


$stmt->execute();
$result = $stmt->get_result();

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
