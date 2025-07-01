<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    echo "<script>alert('Unauthorized access'); window.location.href = 'allocate_supervisor.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['assignment_id'])) {
    $assignment_id = intval($_POST['assignment_id']);
    $conn = db_connect();

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("DELETE FROM supervisor_map WHERE id = ?");
    $stmt->bind_param("i", $assignment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Supervisor assignment deleted successfully.'); window.location.href = 'allocate_supervisor.php';</script>";
    } else {
        echo "<script>alert('Error deleting assignment.'); window.location.href = 'allocate_supervisor.php';</script>";
    }

    $stmt->close();
    mysqli_close($conn);
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'allocate_supervisor.php';</script>";
}
?>
