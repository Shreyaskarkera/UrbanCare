<?php
include './sessionValidate.php'; 
// include '../connection.php';
$conn = db_connect();
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/custom.css">
    <style>
    body {
        padding-top: 70px;
    }

    .rating i {
        font-size: 24px;
        cursor: pointer;
        color: gray;
    }

    .card {
        max-width: 800px;     /* consistent max size */
        width: 100%;          /* full width on small screens */
        margin: auto;
        margin-bottom: 5%;
        padding: 20px;
    }

    .footer {
        margin-top: 10%;
    }

    /* Media Query for small screens */
    @media (max-width: 576px) {
        .card {
            padding: 15px;
            font-size: 0.9rem;
        }
    }

    /* Media Query for large screens (optional fine-tuning) */
    @media (min-width: 992px) {
        .card {
            padding: 30px;
        }
    }
</style>

</head>
<body>
    
        <?php include './nav.php'; ?>
  
    
    <div class="container mt-5">
        <div class="card p-4">
            <h2 class="text-center">Submit Your Feedback</h2>
            <form method="POST" action="submit_feedback.php">
                <div class="mb-3">
                    <label for="complaint_id" class="form-label">Select Complaint</label>
                    <select class="form-select" name="complaint_id" required>
                        <?php
                        $result = mysqli_query($conn, "SELECT id FROM complaints WHERE user_id = '$user_id'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['id']}'>Complaint #{$row['id']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="feedback_type" class="form-label">Feedback Type</label>
                    <select class="form-select" name="feedback_type" required>
                        <?php
                        $result = mysqli_query($conn, "SELECT id, type_name FROM feedback_types");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['id']}'>{$row['type_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3 text-center">
                    <label class="form-label">Rating</label>
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <i class="fa fa-star" data-value="<?php echo $i; ?>"></i>
                        <?php } ?>
                        <input type="hidden" name="rating" id="rating" required>
                    </div>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="comment" rows="4" placeholder="Write your feedback..." required></textarea>
                </div>
                <button type="submit" class="btn btn-warning w-100">Submit</button>
            </form>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.querySelectorAll('.rating i').forEach(star => {
        star.addEventListener('click', function () {
            let value = this.getAttribute('data-value');
            document.getElementById('rating').value = value;
            
            // Reset all stars
            document.querySelectorAll('.rating i').forEach(s => {
                s.style.color = "gray";
            });

            // Highlight selected stars in gold
            for (let i = 0; i < value; i++) {
                document.querySelectorAll('.rating i')[i].style.color = "gold";
            }
        });
    });
</script>

    <?php include './footer.php'; ?>
</body>
</html>