<?php
session_start();

// Check if session is set and the role is USER
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    // Redirect to index page if the session does not exist or role is not USER
    header("Location: ../index.html");
    exit();
}

if ($_SESSION['role_name'] != 'USER') {
    echo "<script>
    alert('Dont have access to this page');window.location.href = '../login.php';
   </script>";
}

$name = $_SESSION['name'];

$user_id = $_SESSION['user_id'];


?>

<?php
require_once("../connection.php");
$conn = db_connect();

$sql = "SELECT * FROM complaints WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


db_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Care - View Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- dataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <style>
        /* Custom color scheme */
        .navbar {
            background-color: #0077B6;
            /* Light Blue */
        }

        .navbar-brand h1 {
            color: white;
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            color: rgb(73, 73, 73) !important;
            /* Yellow on hover */
        }

        .badge-warning {
            background-color: #ff9800;
            /* Orange for Pending */
        }

        .badge-success {
            background-color: #4caf50;
            /* Green for Resolved */
        }

        .btn-primary {
            background-color: #007bff;
            /* Blue buttons */
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
            /* Green buttons */
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-danger {
            background-color: #dc3545;
            /* Red buttons */
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        footer {
            background-color: #343a40;
            color: #f8f9fa;
        }

        footer a {
            color: #4a90e2;
            /* Blue links in footer */
        }

        footer a:hover {
            color: #007bff;
            /* Darker blue on hover */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <h1>Urban Care</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./complaint_raise.php">Complaints</a></li>
                    <li class="nav-item"><a class="nav-link active" href="view_complaints.php">View Complaints</a></li>
                    <li class="nav-item"><a class="nav-link" href="./user_help.html">Help</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="#">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> John Doe
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="./view_profile.php">View Profile</a></li>
                            <li><a class="dropdown-item" href="./update_profile.php">Update Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- View Complaints Section -->
    <section class="container my-5 pt-5">
        <h2 class="text-center mb-4">View Complaints</h2>
        <div class="table-responsive">
            <table class="table table-striped" id="complaintsTable">
                <thead>
                    <tr>
                        <th>SNO</th>
                        <th>Complaint ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $serial_no = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$serial_no++ ."</td>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["title"] . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            echo "<td>" . $row["complaint_status"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No complaints found.</td></tr>";
                    }
                    ?>
                </tbody>

                <!-- if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Complaint ID: " . $row["complaint_id"] . "<br>";
        echo "Title: " . $row["title"] . "<br>";
        echo "Description: " . $row["description"] . "<br>";
        echo "Status: " . $row["status"] . "<br>";
        echo "Date: " . $row["created_at"] . "<br>";
        echo "<hr>";
    }
} else {
    echo "No complaints found.";
} -->



  

                    <!-- Complaint 1 -->
                    <!-- <tr>
                        <td>001</td>
                        <td>Garbage Overflow</td>
                        <td>Garbage overflowing near the park entrance.</td>
                        <td>2025-01-15</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm view-btn" data-id="001" data-issue="Garbage Overflow"
                                data-status="Pending" data-filed-on="2025-01-15" data-image="path/to/image1.jpg">View</a>
                        </td>
                    </tr>
                   
                    <tr>
                        <td>002</td>
                        <td>Illegal Dumping</td>
                        <td>Unauthorized dumping of construction debris in residential area.</td>
                        <td>2025-01-10</td>
                        <td><span class="badge bg-success">Resolved</span></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm view-btn" data-id="002" data-issue="Illegal Dumping"
                                data-status="Resolved" data-filed-on="2025-01-10" data-image="path/to/image2.jpg">View</a>
                        </td>
                    </tr>
          
                    <tr>
                        <td>003</td>
                        <td>Broken Streetlight</td>
                        <td>The streetlight near the bus stop has been broken for days.</td>
                        <td>2025-01-08</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm view-btn" data-id="003" data-issue="Broken Streetlight"
                                data-status="Pending" data-filed-on="2025-01-08" data-image="path/to/image3.jpg">View</a>
                        </td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- Complaint Details Modal -->
    <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="complaintModalLabel">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Complaint ID:</strong> <span id="modalComplaintId"></span></p>
                    <p><strong>Issue:</strong> <span id="modalIssue"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    <p><strong>Filed On:</strong> <span id="modalFiledOn"></span></p>
                    <div>
                        <p><strong>Image:</strong></p>
                        <img id="modalImage" src="#" alt="Complaint Image" class="img-fluid border rounded">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="pt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-uppercase">Urban Care</h5>
                    <p>Committed to improving city life by addressing public complaints about civic issues. Together, let's make our city clean and sustainable.</p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-uppercase">Contact Us</h5>
                    <p>Email: support@urbancare.com</p>
                    <p>Phone: +1 234 567 890</p>
                    <div>
                        <a href="#" class="text-dark me-3"><i class="bi bi-facebook text-light"></i></a>
                        <a href="#" class="text-dark me-3"><i class="bi bi-twitter text-light"></i></a>
                        <a href="#" class="text-dark me-3"><i class="bi bi-instagram text-light"></i></a>
                        <a href="#" class="text-dark"><i class="bi bi-linkedin text-light"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4 border-top pt-3">
                <p>&copy; 2025 Urban Care. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

   
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#complaintsTable').DataTable();
        });
    </script>
</body>

</html>