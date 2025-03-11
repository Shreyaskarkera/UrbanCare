<?php
// Connect to database
require 'db_connection.php';

header('Content-Type: application/json');

$query = "SELECT id, complaint_type, status, description, created_at, latitude, longitude FROM complaints WHERE latitude IS NOT NULL AND longitude IS NOT NULL";
$result = mysqli_query($conn, $query);

$complaints = [];
while ($row = mysqli_fetch_assoc($result)) {
    $complaints[] = $row;
}

echo json_encode($complaints);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #map { height: 500px; width: 100%; }
    </style>
</head>
<body>
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
                url: 'fetch_complaints.php', // Fetch complaints with latitude & longitude
                type: 'GET',
                dataType: 'json',
                success: function(complaints) {
                    complaints.forEach(function(complaint) {
                        var marker = L.marker([complaint.latitude, complaint.longitude]).addTo(map);
                        
                        // Bind popup with complaint details
                        marker.bindPopup(
                            `<b>Complaint ID:</b> ${complaint.id}<br>
                             <b>Type:</b> ${complaint.complaint_type}<br>
                             <b>Status:</b> ${complaint.status}<br>
                             <b>Description:</b> ${complaint.description}<br>
                             <b>Date:</b> ${complaint.created_at}`
                        );
                    });
                },
                error: function(error) {
                    console.error("Error loading complaints: ", error);
                }
            });
        }

        // Load complaints on map
        loadComplaintMarkers();
    </script>
</body>
</html>
