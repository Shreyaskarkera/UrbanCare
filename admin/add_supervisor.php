<?php
include '../connection.php';

$conn = db_connect();
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role_id = 2; // Supervisors have role_id = 2
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // 🔹 Insert Supervisor into User Table
    $query = "INSERT INTO users (name, email, phone_no, role_id) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt !== false) {
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $phone, $role_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Supervisor added successfully!'); window.location.href = 'supervisor.html';</script>";
        } else {
            echo "<script>alert('Error inserting data: " . mysqli_stmt_error($stmt) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Database query preparation failed: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supervisor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(255, 255, 255);
            color: white;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
            background-color: rgb(28, 104, 46) !important;
            border-radius: 10px;
        }
        .navbar {
            background-color: #28a745 !important;
        }
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        .navbar-nav .nav-link {
            color: white !important;
            transition: 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: #d4d4d4 !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="./Tasks.html">Tasks</a></li>
                    <li class="nav-item"><a class="nav-link" href="./Reports.html">Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="./supervisor.html">Supervisors</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2 class="text-center">Add Supervisor</h2>
        <form action="add_supervisor.php" method="POST" onsubmit="return validateForm()">
            <input type="hidden" id="role_id" name="role_id" value="2">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Supervisor</button>
        </form>
    </div>
    <script>
        function validateForm() {
            let phone = document.getElementById("phone").value;
            if (!/^\d{10}$/.test(phone)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }
            return true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
