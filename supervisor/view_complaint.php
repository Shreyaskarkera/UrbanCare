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
    <link rel="stylesheet" href="css/nav.css">

</head>

<body>
<?php include './nav.php'; ?>
    <!-- View Profile Modal -->
    <div class="container" style="margin-top: 5rem;">
    <div class="row">
        <!-- Complaint Details -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title" id="complaintTitle"><?php echo $complaint['title']; ?></h3>
                    <p><strong>Raised By:</strong> <span id="userId"><?php echo $complaint['raised_by']; ?></span></p>
                    <p><strong>Phone:</strong> <span id="phone"><?php echo $complaint['phone_no']; ?></span></p>
                    <p><strong>Complaint Type:</strong> <span id="complaintTypeId"><?php echo $complaint['complaint_type']; ?></span></p>
                    <p><strong>Location:</strong> <span id="location"><?php echo $complaint['location']; ?></span></p>
                    <p><strong>Description:</strong> <span id="description"><?php echo $complaint['description']; ?></span></p>
                    <p><strong>Status:</strong> <span id="complaintStatus"><?php echo $complaint['complaint_status']; ?></span></p>
                    <p><strong>Action Date:</strong> <span id="complaintActionDate"><?php echo $complaint['action_date']; ?></span></p>
                    <p><strong>Resolved Date:</strong> <span id="complaintResolvedDate"><?php echo $complaint['resolved_date']; ?></span></p>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="col-md-6 mb-4">
            <div id="map" style="height: 420px; border-radius: 8px;"></div>
        </div>

        <!-- Complaint Image -->
        <div class="col-12 mb-5">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4 class="card-title">Complaint Image</h4>
                    <img id="photo" src="<?php echo "../" . $complaint['photo']; ?>" alt="Complaint Photo" class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
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