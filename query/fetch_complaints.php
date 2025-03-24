<?php

require '../connection.php';

$conn = db_connect();
if (!$conn) {
    die(json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]));
}
$supervisor_id=$_GET['supervisor_id'];

$query = "SELECT c.id, ct.name AS complaint_type, c.complaint_status AS status, c.description, c.created_at, c.latitude, c.longitude 
          FROM complaints c JOIN complaint_type ct ON c.complaint_type_id=ct.id 
          WHERE c.supervisor_id='$supervisor_id' AND c.latitude IS NOT NULL AND c.longitude IS NOT NULL AND c.complaint_status in ('Open','In-Progress')";

$result = mysqli_query($conn, $query);

if (!$result) {
    die(json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]));
}

$complaints = mysqli_fetch_all($result, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($complaints);

mysqli_free_result($result);
db_close($conn);

?>