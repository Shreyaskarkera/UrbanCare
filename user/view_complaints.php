<?php include './sessionValidate.php'; ?>

<?php
require_once("../connection.php");
$conn = db_connect();

$sql = "SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

db_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Care</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- dataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="./css/custom.css">

    <style>
      
        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
        }

        /* Responsive Buttons */
        .btn-sm {
            font-size: 0.875rem;
            padding: 0.4rem 0.8rem;
        }

        /* Make Modal Image Fully Responsive */
        #modalImage {
            max-width: 100%;
            height: auto;
            display: block;
            margin: auto;
        }

        /* Center text in table on small screens */
        @media (max-width: 576px) {

            .table th,
            .table td {
                font-size: 0.9rem;
               
            }
        }
    </style>

</head>

<body>

    <?php include './nav.php'; ?>

    <!-- View Complaints Section -->
    <section class="container my-5 pt-5">
        <h2 class="text-center mb-4">View Complaints</h2>
        <div class="table-responsive mb-5">
            <table class="table table-striped" id="complaintsTable">
                <thead>
                    <tr>
                        <th>SNO</th>
                        <th>Complaint ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th> <!-- New Column for the View button -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $serial_no = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $serial_no++ . "</td>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["title"] . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            $status = $row["complaint_status"];

                            $statusColors = [
                                'Open' => 'style="color: blue; font-weight: bold;"',
                                'In-Progress' => 'style="color: orange; font-weight: bold;"',
                                'Resolved' => 'style="color: green; font-weight: bold;"',
                                'Rejected' => 'style="color: red; font-weight: bold;"'
                            ];

                            echo "<td " . ($statusColors[$status] ?? '') . ">" . htmlspecialchars($status) . "</td>";
                            echo "<td><a href='./complaint_details.php?id=" . $row["id"] . "' class='btn btn-primary btn-sm'>View</a></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Complaint Details Modal -->
    <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="complaintModalLabel">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Complaint ID:</strong> <span id="modalComplaintId"></span></p>
                    <p><strong>Issue:</strong> <span id="modalIssue"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    <p><strong>Filed On:</strong> <span id="modalFiledOn"></span></p>
                    <div>
                        <p><strong>Image:</strong></p>
                        <img id="modalImage" src="#" alt="Complaint Image" class="img-fluid border rounded">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <?php include './footer.php'; ?>

    <script>
        $(document).ready(function() {
            $('#complaintsTable').DataTable({
                "responsive": true
            });
        });
    </script>

</body>

</html>