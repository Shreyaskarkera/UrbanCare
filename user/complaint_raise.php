<?php 
include './sessionValidate.php';
require_once '../image_upload.php';
require_once("../connection.php");

// Check if form is submitted
if (isset($_POST['raise_complaint'])) {
    $complaint_title = $_POST['complaint_title'] ?? null;
    $complaint_type = $_POST['complaint_type'] ?? null;
    $place = $_POST['place'] ?? null;
    $description = $_POST['complaint_description'] ?? null;
    $location = $_POST['location'] ?? null;
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    // Ensure user is logged in
    $user_id = $_SESSION['user_id'] ?? null;
    $created_at = date('Y-m-d H:i:s');
    $image_url = null;

    // Check for missing fields
    if (!$complaint_title || !$complaint_type || !$description || !$place || !$latitude || !$longitude || !$user_id) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    // Database connection
    $conn = db_connect();

    // Handle image upload
    if (isset($_FILES["complaint_image"])) {
        $relativeDir = "uploads/" . $user_id . "/complain"; // Custom folder inside root
        $result = uploadImage($relativeDir, $_FILES["complaint_image"]);
        $image_url = $result['path'];
    }

    // Fetch active supervisor for the selected place
    $sql_supervisor = "
        SELECT u.id AS supervisor_id 
        FROM supervisor_map sm
        JOIN users u ON sm.supervisor_id = u.id
        WHERE sm.place_id = ? AND u.is_active = 1
        LIMIT 1
    ";

    $stmt_supervisor = $conn->prepare($sql_supervisor);
    $stmt_supervisor->bind_param("s", $place);
    $stmt_supervisor->execute();
    $result = $stmt_supervisor->get_result();
    $supervisor_map = ($result->num_rows > 0) ? $result->fetch_assoc() : null;
    $stmt_supervisor->close();

    // If no active supervisor is assigned, prevent complaint submission
    if ($supervisor_map === null) {
        echo "<script>alert('Cannot raise a complaint. No active supervisor assigned to this place. Please contact the admin.'); window.location.href='complaint_raise.php';</script>";
        db_close($conn);
        exit();
    }

    // Insert complaint
    $sql = "INSERT INTO complaints (user_id, title, complaint_type_id, photo, description, place_id, location, latitude, longitude, supervisor_id, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issssssssss", 
            $user_id, $complaint_title, $complaint_type, $image_url, 
            $description, $place, $location, $latitude, $longitude, 
            $supervisor_map['supervisor_id'], $created_at
        );

        if ($stmt->execute()) {
            echo "<script>alert('Complaint submitted successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error submitting complaint. Please try again.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database error: Unable to submit complaint.'); window.history.back();</script>";
    }

    db_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raise a Complaint</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="./css/custom.css">

    <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1/dist/leaflet.min.css">
    <link type="text/css" rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@2/dist/Control.Geocoder.min.css">

    <style>
    .form-container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 100px;
    }

    .status-message {
        font-size: 14px;
        margin-top: 5px;
    }

    #map {
        height: 400px;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        margin-top: 10px;
    }
    </style>
</head>

<body>


    <?php include './nav.php'; ?>

    <div class="container mt-5 pt-3">
        <h2 class="text-center my-4">Raise a Complaint</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="form-container">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="complaint_title" class="form-label">Complaint Title</label>
                            <input type="text" name="complaint_title" class="form-control" id="complaint_title"
                                placeholder="Title">
                            <label for="complaint_type" class="form-label mt-3">Complaint Type</label>
                            <select class="form-select" id="complaint_type" name="complaint_type" required>
                                <option value="" disabled selected>Select Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="complaint_description" class="form-label">Description</label>
                            <textarea class="form-control" id="complaint_description" name="complaint_description"
                                rows="4" placeholder="Describe the issue" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="place" class="form-label">Place</label>
                            <select class="form-select" id="place" name="place" required onchange="updateMap()">
                                <option value="" disabled selected>Select Place</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Images</label>
                            <input type="file" class="form-control" id="complaint_images" name="complaint_image"
                                accept="image/*">
                            <small class="text-muted">Upload images to auto-detect location.</small>
                            <p id="gps-status" class="status-message text-danger"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" readonly required>
                            <div id="map"></div>
                        </div>
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" name="raise_complaint">Submit
                                Complaint</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1/dist/leaflet-src.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@2/dist/Control.Geocoder.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php include './footer.php'; ?>

    <script>
    const apiKey = '';
    const locationInput = document.getElementById('location');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    const map = L.map('map').setView([20.5937, 78.9629], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([20.5937, 78.9629], {
        draggable: true
    }).addTo(map);
    marker.on('dragend', function() {
        const position = marker.getLatLng();
        latitudeInput.value = position.lat;
        longitudeInput.value = position.lng;
        fetchLocation(position.lat, position.lng);

    });

    function fetchLocation(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
            .then(response => response.json())
            .then(data => {
                locationInput.value = data.display_name;
            })
            .catch(error => console.error('Error fetching location:', error));
    }


    L.Control.geocoder({
            defaultMarkGeocode: false,
            showResultIcons: true,
            autoComplete: true,
            placeholder: "Search address...",
        }).on('markgeocode', function(e) {
            const latlng = e.geocode.center;
            map.setView(latlng, 13);
            marker.setLatLng(latlng);
            latitudeInput.value = latlng.lat;
            longitudeInput.value = latlng.lng;
            locationInput.value = e.geocode.name;
        })
        .addTo(map);

    function updateMap() {
        const select = document.getElementById("place");
        const placeName = select.options[select.selectedIndex].text;

        if (placeName) {
            console.log(placeName);
            var geocoder = L.Control.Geocoder.nominatim({
    geocodingQueryParams: {
        countrycodes: 'IN', // Restrict results to India
        viewbox: "74.0, 15.0, 78.5, 11.5", // Bounding box covering Karnataka
        bounded: 1 // Prioritize results within Karnataka
    }
});


            geocoder.geocode(placeName, function(results) {
                if (results.length > 0) {
                    const latlng = results[0].center;
                    map.setView(latlng, 13);
                    marker.setLatLng(latlng);
                    latitudeInput.value = latlng.lat;
                    longitudeInput.value = latlng.lng;
                    locationInput.value = results[0].name;
                    console.log(results)
                } else {
                    alert("Location not found!");
                }
            });
        }
    }

    $(document).ready(function() {

        // Send an AJAX request to fetch the complaint types
        $.ajax({
            url: '../query/fetch_complaint_type.php', // URL of the PHP file that fetches data
            type: 'GET',
            dataType: 'json', // Expecting JSON response
            success: function(response) {
                // Check if data is returned
                if (response.length > 0) {

                    console.log(response);
                    // Loop through the response and populate the dropdown
                    $.each(response, function(index, complaint) {
                        var option = $('<option></option>').val(complaint.id).text(complaint
                            .complaint_name);
                        $('#complaint_type').append(option);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", error);
            }
        });


        $.ajax({
            url: '../query/fetch_place.php', // URL of the PHP file that fetches data
            type: 'GET',
            dataType: 'json', // Expecting JSON response
            success: function(response) {
                // Check if data is returned
                if (response.length > 0) {

                    console.log(response);
                    // Loop through the response and populate the dropdown
                    $.each(response, function(index, place) {
                        var option = $('<option></option>').val(place.id).text(place
                            .place_name);
                        $('#place').append(option);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", error);
            }
        });
    });
    </script>

</body>

</html>