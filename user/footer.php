<!-- Footer -->
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<footer class="footer text-white text-center py-4 fs-6" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row">
            <!-- About -->
            <div class="col-md-6 mb-3">
                <h5>About Urban Care</h5>
                <p class="small">
                    Urban Care is dedicated to maintaining clean and sustainable city environments by empowering citizens to report issues directly to their local corporation.
                </p>
            </div>

        

            <!-- Contact & Social -->
            <div class="col-md-6 mb-3">
                <h5>Contact Us</h5>
                <p class="small mb-1">Email: support@urbancare.in</p>
                <p class="small">Phone: +91 98765 43210</p>
                <div>
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-white">
        <p class="mb-0">&copy; 2025 Urban Care. All rights reserved.</p>
    </div>
</footer>


<?php
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 0;
}
?>

<script>
    $(document).ready(function() {
        function fetchNotifications() {
            $.ajax({
                url: "../query/fetch_user_notification.php",
                type: "GET",
                data: {
                    user_id: <?php echo json_encode($user_id); ?>
                },
                dataType: "json",
                success: function(response) {
                    let count = response.length || 0;
                    $("#notificationBadge").text(count).toggle(count > 0);
                },
                error: function() {
                    console.error("Failed to fetch notifications.");
                }
            });
        }

        fetchNotifications();
        setInterval(fetchNotifications, 60000);
    });
</script>



