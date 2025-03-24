  <!-- Footer -->
  <footer class="footer text-white mt-5 p-4 text-center">
      &copy; 2025 Urban Care. All rights reserved.
  </footer>
  <script>
        $(document).ready(function() {
                    $.ajax({
                        url: "../query/fetch_user_notification.php", // Adjust path if needed
                        type: "GET",
                        data: {
                            user_id: <?php echo $user_id; ?>
                        }, // Replace with dynamic user_id
                        dataType: "json",
                        success: function(response) {
                            let count = response.length; // Count unread notifications
                            $("#notificationBadge").text(count); // Update count badge
                        }
                    });
                });
    </script>
