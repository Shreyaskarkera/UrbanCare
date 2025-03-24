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

$supervisor_id=$_SESSION['user_id'];
$name=$_SESSION['name'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/nav.css">
    <style>
    #map {
        height: 500px;
        width: 100%;
        margin-top: 5rem;
    }
    </style>
</head>

<body>
<?php include './nav.php'; ?>
    <div id="map"></div>

    <script>
    // Initialize the map
    var map = L.map('map').setView([12.9716, 77.5946], 12); // Default view (Bangalore example)

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Function to fetch complaints and place markers
    function loadComplaintMarkers() {
        $.ajax({
            url: '../query/fetch_complaints.php', // Fetch complaints with latitude & longitude
            type: 'GET',
            data:{"supervisor_id":<?php echo $supervisor_id;?>},
            dataType: 'json',
            
            success: function(complaints) {
                console.log(complaints);
                // map.clearLayers();

                let bounds = L.latLngBounds();

                complaints.forEach(function(complaint) {

                    var marker = L.marker([parseFloat(complaint.latitude), parseFloat(complaint
                        .longitude)]).addTo(map);

                    // Bind popup with complaint details
                    marker.bindPopup(
                        `<b>Complaint ID:</b> ${complaint.id}<br>
                             <b>Type:</b> ${complaint.complaint_type}<br>
                             <b>Status:</b> ${complaint.status}<br>
                             <b>Description:</b> ${complaint.description}<br>
                             <b>Date:</b> ${complaint.created_at}`
                    );

                    bounds.extend(marker.getLatLng());
                });

                // If markers exist, fit map to bounds, otherwise reset to default view
                if (bounds.isValid()) {
                    map.fitBounds(bounds, {
                        padding: [50, 50]
                    });
                } else {
                    map.setView([12.9716, 77.5946], 12);
                }
            },
            error: function(error) {
                console.error("Error loading complaints: ", error);
            }
        });
    }

    // Load complaints on map
    loadComplaintMarkers();
    </script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>