<?php
include '../connection.php'; // Include the database connection file

$conn = db_connect();
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $id = intval($_POST['id']); // Ensure ID is an integer
    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $status = intval($_POST['status']); // Ensure status is an integer (1 or 0)

    // Update the location in the database
    $query = "UPDATE place SET name = ?, is_active = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sii", $name, $status, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($success) {
            // Redirect back to the manage locations page with success message
            header("Location: location.php?msg=Location updated successfully");
            exit();
        } else {
            echo "Error updating location: " . mysqli_error($conn);
        }
    } else {
        echo "Error in SQL statement: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
