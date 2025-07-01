<?php
session_start(); // Start the session
include '../connection.php'; // Include database connection

// Check if user is logged in and has the correct role (Admin - role_id 3)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    echo "<script>alert('You don’t have access to this page'); window.location.href = '../login.php';</script>";
    exit();
}

$name = $_SESSION['name'];
$conn = db_connect();
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission for supervisor allocation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $place_id = $_POST['place_id'];
    $supervisor_id = $_POST['supervisor_id'];

    if (!empty($place_id) && !empty($supervisor_id)) {
        // Check if a supervisor is already assigned to this place
        $check_stmt = $conn->prepare("SELECT supervisor_id FROM supervisor_map WHERE place_id = ?");
        $check_stmt->bind_param("i", $place_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('Error: This place already has a supervisor assigned. Please choose a different location.'); window.location.href = 'allocate_supervisor.php';</script>";
        } else {
            // Insert into supervisor_map table
            $stmt = $conn->prepare("INSERT INTO supervisor_map (place_id, supervisor_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $place_id, $supervisor_id);

            if ($stmt->execute()) {
                echo "<script>alert('Supervisor allocated successfully!'); window.location.href = 'allocate_supervisor.php';</script>";
            } else {
                echo "<script>alert('Error: Could not allocate supervisor.'); window.location.href = 'allocate_supervisor.php';</script>";
            }
            $stmt->close();
        }

        $check_stmt->close();
    } else {
        echo "<script>alert('Please select both Place and Supervisor.'); window.location.href = 'allocate_supervisor.php';</script>";
    }
}

// Fetch places
$places = $conn->query("SELECT * FROM place");

// Fetch supervisors
$supervisors = $conn->query("SELECT id, name FROM users WHERE role_id = 2");

// Fetch supervisor assignments
$assignments = $conn->query("SELECT sm.id, p.name AS place_name, u.name AS supervisor_name FROM supervisor_map sm 
    JOIN place p ON sm.place_id = p.id 
    JOIN users u ON sm.supervisor_id = u.id");

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocate Supervisor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/css/admin.css">

    <style>
    </style>
</head>

<body>

    <?php include './nav.php'; ?>
    <div class="main-content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Supervisor Assignments</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#allocateModal">Allocate Supervisor</button>
        </div>

        <table id="assignmentsTable"class="table table-bordered table-striped text-center table-container">
        <thead class="table-success">
    <tr>
        <th>Place Name</th>
        <th>Supervisor Name</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php while ($row = $assignments->fetch_assoc()): ?>
        <tr>
            <td><?= $row['place_name'] ?></td>
            <td><?= $row['supervisor_name'] ?></td>
            <td>
                <form action="remove_allocate_supervisor.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                    <input type="hidden" name="assignment_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="allocateModal" tabindex="-1" aria-labelledby="allocateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allocateModalLabel">Allocate Supervisor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select Place:</label>
                            <select name="place_id" class="form-control" required>
                                <option value="">--Select a Place--</option>
                                <?php while ($row = $places->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>"> <?= $row['name'] ?> </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Supervisor:</label>
                            <select name="supervisor_id" class="form-control" required>
                                <option value="">--Select Supervisor--</option>
                                <?php while ($row = $supervisors->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>"> <?= $row['name'] ?> </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Allocate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#assignmentsTable').DataTable();
        });
    </script>
</body>

</html>