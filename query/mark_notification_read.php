<?php

include '../connection.php';

$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $notification_id = $_POST['notification_id'];

    if (!empty($notification_id)) {
        $sql = "UPDATE notifications SET is_read = TRUE WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $notification_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid notification ID"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}

db_close($conn);
?>
