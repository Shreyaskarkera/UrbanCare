<?php

include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $user_id = $_POST['user_id'];


    // Connect to database
    $conn = db_connect();

    $sql = null;
    $message = null;
    switch ($status) {
        case "In-Progress":
            $sql = "UPDATE complaints SET complaint_status='$status' , action_date = NOW()  WHERE id=$id";
            $message = '<span><i class="fas fa-exclamation-circle text-warning me-2"></i>Complaint ID #'.$id.' is In-Progress</span>
            <span class="badge bg-warning ms-2">In Progress</span>';
            break;
        case "Resolved":
            $sql = "UPDATE complaints SET complaint_status='$status' , resolved_date = NOW()  WHERE id=$id";
            $message = '<span><i class="fas fa-check-circle text-success me-2"></i>Complaint ID #'.$id.' has been resolved</span>
            <span class="badge bg-success ms-2">Resolved</span>';
            break;
        case "Rejected":
            $sql = "UPDATE complaints SET complaint_status='$status', action_date = NOW() WHERE id=$id";
            $message = '<span><i class="fas fa-exclamation-triangle text-danger me-2"></i>Complaint ID #'.$id.' has been rejected</span>
            <span class="badge bg-danger ms-2">Rejected</span>';
            break;
    }

    if ($conn->query($sql) === TRUE) {
        $sql_notification = "INSERT INTO notifications(user_id, message, complaint_id) VALUES ('$user_id','$message','$id')";
        $conn->query($sql_notification);
        echo "Status updated to " . strtoupper($status);

    } else {
        echo "Error: " . $conn->error;
    }

    db_close($conn);
}
?>
