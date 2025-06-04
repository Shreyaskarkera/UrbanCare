<?php include './sessionValidate.php'; ?>

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

    <link rel="stylesheet" href="./css/custom.css">

    <!-- dataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
        .footer{
            margin-top: 10%;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include './nav.php'; ?>

    <!-- Notifications Section -->
    <div class="container mt-5 pt-4 mb-5">
        <h1 class="mb-4">Notifications</h1>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Recent Notifications</h5>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php include './footer.php'; ?>
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
                        $.each(response, function(index, notification) {
                            var notifyTime = "<div class='time small'>" + timeAgo(notification.created_at) + "</div>";

                            // Add notification ID and click event
                            var data = `<div class='notification-item' data-id='${notification.id}'  data-complaintId='${notification.complaint_id}' style='cursor: pointer;'> 
                                    ${notification.message} 
                                    ${notifyTime} 
                                </div>`;

                            card.append(data);
                        });

                        // Click event for marking as read and redirecting
                        $(".notification-item").click(function() {
                            var notificationId = $(this).data("id");
                            var complaintId = $(this).data("complaintid");

                            // Mark as read in database
                            $.ajax({
                                url: "../query/mark_notification_read.php",
                                type: "POST",
                                data: {
                                    notification_id: notificationId
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.success) {
                                        console.log("Notification marked as read.");
                                        // Redirect to the View Complaints page
                                        window.location.href = "complaint_details.php?id="+complaintId;
                                    } else {
                                        console.error("Error updating notification:", response.error);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error:", error);
                                }
                            });
                        });
                    } else {
                        card.append("<div class='notification-item'>No Notification Available</div>");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching notifications:", error);
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