<?php

include '../connection.php';
session_start();
$conn = db_connect();

// Check if session is set and the role is USER
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    // Redirect to index page if the session does not exist or role is not USER
    header("Location: ../index.html");
    exit();
}

if ($_SESSION['role_name'] != 'SUPERVISOR') {
    echo "<script>
    alert('Dont have access to this page');window.location.href = '../login.php';
   </script>";
}

$name = $_SESSION['name'];

?>
<?php
$supervisor_id = $_SESSION['user_id'];

$id=$_GET['id'];
echo $id;

$sql = "SELECT c.id, ct.name as complaint_type , c.title, c.description, c.created_at, c.complaint_status, c.latitude, 
c.longitude,u.name as raised_by,u.phone_no,c.location,c.action_date,c.resolved_date,c.photo FROM complaints c 
JOIN complaint_type ct ON c.complaint_type_id = ct.id 
JOIN users u ON u.id =c.user_id WHERE c.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$complaint=null;
if($result->num_rows > 0){
    $complaint=$result->fetch_assoc();
}
else{
    echo "<script>
    alert('complaint not found');window.location.href = './index.php';
   </script>";
}
db_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Care - Supervisor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- dataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Darker Mode, More Appealing Theme */
        body {
            background-color: #2b2f37;
            /* Soft dark background */
            color: #e0e0e0;
            /* Light text */
        }

        .navbar {
            background-color: #3c4a59;
            /* Dark grey with blue undertones */
        }

        .navbar-toggler-icon {
            background-color: #3c4a59;
            /* Light blue toggle icon */
        }

        .navbar-nav .nav-link,
        .navbar-brand {
            color: #f5f5f5;
            /* Soft white text */
        }

        .navbar-nav .nav-link:hover,
        .navbar-brand:hover {
            color: #3498db;
            /* Light Blue Hover */
        }

        .card {
            background-color: #f3f8ff;
            /* Dark grey card background */
            border: 1px solid #4b5563;
            /* Lighter border */
        }

        .card-header {
            background-color: #4b5563;
            /* Darker grey header */
            color: #f5f5f5;
            /* Light text */
        }

        .table {
            background-color: #2f353d;
            /* Darker background for table */
            color: #e0e0e0;
            /* Light text */
        }

        .table-primary {
            background-color: #4b5563;
            /* Header row darker grey */
            color: #f5f5f5;
            /* Light text */
        }

        .badge {
            color: #fff;
            /* White text for badges */
        }

        .badge.bg-warning {
            background-color: #f39c12;
            /* Yellow for Pending */
        }

        .badge.bg-success {
            background-color: #27ae60;
            /* Green for Resolved */
        }

        .badge.bg-info {
            background-color: #2980b9;
            /* Blue for In-Progress */
        }

        .btn-info {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-info:hover {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-success {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        .btn-success:hover {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }

        .btn-secondary {
            background-color: #7f8c8d;
            border-color: #7f8c8d;
        }

        .btn-secondary:hover {
            background-color: #95a5a6;
            border-color: #95a5a6;
        }

        .navbar-toggler-icon {
            background-color: #3c4a59;
            /* Light toggle icon */
        }

        .dropdown-menu {
            background-color: #374151;
            /* Dark dropdown menu */
        }

        footer {
            background-color: #3c4a59;
            /* Dark footer */
            color: #e0e0e0;
            /* Light text */
        }

        footer a {
            color: #3498db;
            /* Light Blue Links */
        }

        footer a:hover {
            color: #f5f5f5;
            /* Light text on hover */
        }
    </style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Urban Care</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./index.php"><i
                                class="fas fa-home me-2"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="complaint_report.html"><i
                                class="fas fa-chart-bar me-2"></i>Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="supervisor_map.html"><i class="fas fa-map-marker-alt me-2"></i>Map</a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Preferences</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li> -->
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="supervisor_notification.html">
                            <i class="fas fa-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>Notification
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i><?php echo $name ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#viewProfileModal">View Profile</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#updateProfileModal">Update Profile</a></li>
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
    <!-- View Profile Modal -->
    <div class="container" style="margin-top:5rem">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title" id="complaintTitle"><?php echo $complaint['title']; ?></h3>
                        <p><strong>Raised By:</strong> <span id="userId"><?php echo $complaint['raised_by']; ?></span></p>
                        <p><strong>Phone:</strong> <span id="userId"><?php echo $complaint['phone_no']; ?></span></p>
                        <p><strong>Complaint Type:</strong> <span id="complaintTypeId"> <?php echo $complaint['complaint_type']; ?></span></p>
                        <p><strong>Location:</strong> <span id="location"><?php echo $complaint['location']; ?></span></p>
                        <p><strong>Description:</strong> <span id="description"><?php echo $complaint['description']; ?></span></p>
                        <p><strong>Status:</strong> <span id="complaintStatus"><?php echo $complaint['complaint_status']; ?></span></p>
                        <p><strong>Action Date:</strong> <span id="complaintActionDate"> <?php echo $complaint['action_date']; ?></span></p>
                        <p><strong>Resolved Date:</strong> <span id="complaintResolvedDate"><?php echo $complaint['resolved_date']; ?></span></p>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id="map" style="height: 420px; border-radius: 8px;"></div>
            </div>
            <div class="col-md-12">
            <h3 class="card-title" id="complaintTitle">Complaint Image</h3>
            <img id="photo" src=" <?php echo "../".$complaint['photo'];?>" alt="Complaint Photo" class="img-fluid rounded">
            </div>
        </div>
    </div>
    

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
        const map = L.map('map').setView([<?php echo $complaint['latitude']; ?>,<?php echo $complaint['longitude']; ?>], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        L.marker([ <?php echo $complaint['latitude']; ?>,<?php echo $complaint['longitude']; ?>]).addTo(map)
            .bindPopup(`<b>${complaint.title}</b><br>${complaint.description}`)
            .openPopup();
        </script>


</body>

</html>