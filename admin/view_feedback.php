<?php
include './admin_session_validation.php';
include '../connection.php';
$conn = db_connect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/admin.css">
    <style>
        body {
            padding-top: 70px;
            /* Adjust for navbar height */
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Sidebar adjustment for smaller screens */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }
        }

        .table-responsive {
            overflow-x: auto;
        }

        .rating i {
            font-size: 20px;
            color: gold;
        }

        .text-secondary {
            color: #ccc !important;
            /* Light grey for empty stars */
        }
    </style>
</head>

<body>
    <?php include './nav.php'; ?>

    <div class="main-content mt-4">
       
<<<<<<< HEAD
            <h2 class="mb-2 text-center">User Feedback</h2>
=======
            <h2 class="mb-2 ">User Feedback</h2>
>>>>>>> 963cf97d0c76debcafe1ed9557be3be99da14b2d
            <div class="table-responsive">
                <table id="feedbackTable" class="table table-bordered table-striped text-center table-container">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Complaint ID</th>
                            <th>Feedback Type</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $query = "SELECT f.id, u.name AS username, f.complaint_id, t.type_name, f.rating, f.comment, f.created_at 
              FROM feedback f 
              JOIN users u ON f.user_id = u.id 
              JOIN feedback_types t ON f.feedback_type = t.id 
              ORDER BY f.created_at DESC";

                        $result = mysqli_query($conn, $query);

                        if (!$result) {
                            die("SQL Error: " . mysqli_error($conn));
                        }

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['complaint_id']}</td>
                <td>{$row['type_name']}</td>
                <td class='rating'>";


                            $rating = (int)$row['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<i class="fa fa-star text-warning"></i>';
                                } else {
                                    echo '<i class="fa fa-star text-secondary"></i>';
                                }
                            }

                            echo "</td>
                <td>{$row['comment']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
                        }
                        ?>
                    </tbody>

                </table>

                <!-- Include DataTables and jQuery -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                <!-- Initialize DataTable -->
                <script>
                    $(document).ready(function() {
                        $('#feedbackTable').DataTable({
                            "paging": true, // Enables pagination
                            "searching": true, // Enables search bar
                            "ordering": true, // Enables sorting
                            "info": true, // Shows table information
                            "lengthMenu": [10, 25, 50, 100], // Page length options
                            "columnDefs": [{
                                    "orderable": false,
                                    "targets": [4, 5]
                                } // Disable sorting on Rating and Comment columns
                            ]
                        });
                    });
                </script>

            </div>
    </div>
</body>

</html>