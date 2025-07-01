<?php
header('Content-Type: application/json');
session_start();
include '../connection.php';
$conn = db_connect();

$response = ["success" => false, "message" => "Invalid request"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'] ?? null;
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if ($user_id && !empty($old_password) && !empty($new_password)) {
        // Fetch the user's current password hash
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            $stmt->close();

            // Verify old password
            if (password_verify($old_password, $hashed_password)) {
                // Hash the new password
                $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update password in database
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    $response = ["success" => true, "message" => "Password changed successfully"];
                } else {
                    $response["message"] = "Database update failed.";
                }
                $update_stmt->close();
            } else {
                $response["message"] = "Old password is incorrect.";
            }
        } else {
            $response["message"] = "User not found.";
        }
    } else {
        $response["message"] = "Missing required fields.";
    }
}

echo json_encode($response);
?>
