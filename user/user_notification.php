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
$user_id = $_SESSION['user_id']
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Care - Notifications</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0077B6;
            /* Soft Blue */
            --secondary-color: #9e9e9e;
            --toggle-icon-secondary-color: #ffffff00;
            /* Vibrant Green */
            --accent-color: #FF6F61;
            /* Warm Coral */
            --background-color: #F1F1F1;
            /* Soft Gray */
            --text-color: #333333;
            /* Charcoal Gray */
            --footer-color: #2A3D66;
            /* Deep Slate */
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .navbar {
            background-color: var(--primary-color);
        }

        .navbar-brand h1 {
            color: white;
        }

        .navbar-nav .nav-link {
            color: white;
        }

        .navbar-nav .nav-link:hover {
            color: rgb(73, 73, 73) !important;
        }

        /* Custom Toggle Icon */
        .navbar-toggler-icon {
            background-color: var(--toggle-icon-secondary-color);
            /* Change the toggle icon color */
        }

        .navbar-toggler-icon:hover {
            border: 1px solid var(--secondary-color);
            /* Optional: Add border for better visibility */
        }

        .navbar-toggler-icon:hover {
            background-color: var(--toggle-icon-secondary-color);
            /* Hover effect */
        }

        .navbar-toggler-icon:focus {
            box-shadow: none;
            /* Removes the blue shadow when focused */
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .footer {
            background-color: var(--footer-color);
            color: white;
        }

        .card {
            background-color: white;
            border: 1px solid var(--primary-color);
        }

        .card-title {
            color: var(--primary-color);
        }

        .accordion-button {
            background-color: var(--primary-color);
            color: white;
        }

        .accordion-button:hover {
            background-color: var(--secondary-color);
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--secondary-color);
        }

        .list-group-item {
            background-color: white;
            color: var(--text-color);
        }

        .list-group-item:hover {
            background-color: var(--secondary-color);
            color: white;
        }
        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #4b5563;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item .time {
            color: #95a5a6;
        }

        .notification-item .badge {
            font-size: 0.8rem;
            padding: 5px 10px;
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#carouselExampleControlsNoTouching">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./complaint_raise.php">Complaints</a></li>
                    <li class="nav-item"><a class="nav-link" href="./view_complaints.php">View Complaints</a></li>
                    <li class="nav-item"><a class="nav-link" href="user_help.html">Help</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="./user_notification.php">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo $name; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="./view_profile.php">View Profile</a></li>
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

    <!-- Notifications Section -->
    <div class="container mt-5 pt-4">
        <h1 class="mb-4">Notifications</h1>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Recent Notifications</h5>
            </div>
            <div class="card-body">
                <!-- Notification Items -->
                <!-- <div class="notification-item">
                    <span><i class="fas fa-exclamation-circle text-warning me-2"></i>New complaint reported in Zone 1</span>
                    <span class="badge bg-warning ms-2">New</span>
                    <div class="time small">5 minutes ago</div>
                </div>
                <div class="notification-item">
                    <span><i class="fas fa-check-circle text-success me-2"></i>Complaint ID #23 has been resolved</span>
                    <span class="badge bg-success ms-2">Resolved</span>
                    <div class="time small">2 hours ago</div>
                </div>
                <div class="notification-item">
                    <span><i class="fas fa-info-circle text-info me-2"></i>Reminder: Weekly report pending</span>
                    <span class="badge bg-info ms-2">Info</span>
                    <div class="time small">Yesterday</div>
                </div>
                <div class="notification-item">
                    <span><i class="fas fa-exclamation-triangle text-danger me-2"></i>High-priority complaint in Zone 5</span>
                    <span class="badge bg-danger ms-2">Urgent</span>
                    <div class="time small">3 days ago</div>
                </div> -->

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function() {
           
            getUserNotification(<?php echo $user_id ?>);
        });

        function getUserNotification(user_id) {

            console.log(user_id);

            $.ajax({
                url: "../query/fetch_user_notification.php",
                type: "GET",
                data: {
                    user_id: user_id
                },
                dataType: "json",
                success: function(response) {
                    var card = $(".card-body"); // Fix: Added "." to select the correct class
                    card.empty(); // Clear previous notifications

                    console.log(response); // Debugging: Check the response

                    if (response.length > 0) {
                        $.each(response, function(index, notification) { // Fix: Changed 'notifications' to 'response'
                            var notifyTime = "<div class='time small'>" + timeAgo(notification.created_at) + "</div>";
                            var data = "<div class='notification-item'>" + notification.message + notifyTime + "</div>";

                            console.log(data)
                            card.append(data);
                        });
                    } else {
                        card.append("<div class='notification-item'>No Notification Available</div>");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching notifications:", error); // Logs error in console
                    $(".card-body").append("<div class='notification-item text-danger'>Error loading notifications.</div>");
                }
            });

        }



        function timeAgo(datetime) {
            let timestamp = new Date(datetime).getTime(); // Convert to milliseconds
            let now = new Date().getTime(); // Current time in milliseconds
            let difference = Math.floor((now - timestamp) / 1000); // Convert to seconds

            if (difference < 60) {
                return `${difference} seconds ago`;
            } else if (difference < 3600) {
                return `${Math.floor(difference / 60)} minutes ago`;
            } else if (difference < 86400) {
                return `${Math.floor(difference / 3600)} hours ago`;
            } else {
                return `${Math.floor(difference / 86400)} days ago`;
            }
        }
    </script>
</body>

</html>