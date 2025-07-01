<?php
include '../connection.php';
session_start();
$conn = db_connect();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header("Location: ../index.html");
    exit();
}

if ($_SESSION['role_name'] != 'SUPERVISOR') {
    echo "<script>
    alert('Dont have access to this page');window.location.href = '../login.php';
   </script>";
}

$supervisor_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
            margin-top: 75px;
            padding: 1rem;
        }

        .map-container {
            width: 100%;
            max-width: 1200px;
            height: 70vh;
            margin: 2rem auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        #map {
            width: 100%;
            height: 100%;
        }

        @media (max-width: 768px) {
            .map-container {
                height: 60vh;
            }
        }

        @media (max-width: 480px) {
            .map-container {
                height: 50vh;
            }
        }
    </style>
</head>

<body>
    <?php include './nav.php'; ?>
    <div class="container">
        <h2>Complaint Map</h2>
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([12.9716, 77.5946], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        function loadComplaintMarkers(statusFilter = "all") {
            $.ajax({
                url: '../query/fetch_complaints.php',
                type: 'GET',
                data: { "supervisor_id": <?php echo $supervisor_id; ?> },
                dataType: 'json',
                success: function(complaints) {
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            map.removeLayer(layer);
                        }
                    });
                    let bounds = L.latLngBounds();
                    complaints.forEach(function(complaint) {
                        if (statusFilter === "all" || complaint.status.toLowerCase() === statusFilter) {
                            var marker = L.marker([parseFloat(complaint.latitude), parseFloat(complaint.longitude)]).addTo(map);
                            marker.bindPopup(
                                `<b>Complaint ID:</b> ${complaint.id}<br>
                                 <b>Type:</b> ${complaint.complaint_type}<br>
                                 <b>Status:</b> ${complaint.status}<br>
                                 <b>Description:</b> ${complaint.description}<br>
                                 <b>Date:</b> ${complaint.created_at}`
                            );
                            bounds.extend(marker.getLatLng());
                        }
                    });
                    if (bounds.isValid()) {
                        map.fitBounds(bounds, {padding: [50, 50]});
                    } else {
                        map.setView([12.9716, 77.5946], 12);
                    }
                },
                error: function(error) {
                    console.error("Error loading complaints: ", error);
                }
            });
        }

        $(document).ready(function() {
            loadComplaintMarkers();
            $('#statusFilter').on('change', function() {
                loadComplaintMarkers(this.value);
            });
        });
    </script>
</body>

</html>
