<?php
session_start();
require_once '../connection.php';
require_once '../authentication.php';

$conn = db_connect();


$current_url = $_SERVER['REQUEST_URI'];


if (
    !isset($_SESSION['role_name']) ||
    (strpos($current_url, '/supervisor') !== false && $_SESSION['role_name'] === 'SUPERVISOR')
) {
    $_SESSION['redirect_back'] = $current_url;
}


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header("Location: ../login.php");
    exit();
}


if (!isset($_SESSION['role_name'])) {
    $role = getRoleById($_SESSION['role_id']);
    if ($role) {
        $_SESSION['role_name'] = strtoupper($role['name']);
    } else {
        echo "<script>alert('Unable to fetch user role.'); window.location.href='../login.php';</script>";
        exit();
    }
<<<<<<< HEAD
}


if ($_SESSION['role_name'] !== 'SUPERVISOR') {
    $role_redirect = strtolower($_SESSION['role_name']) . "/";
    echo "<script>
        alert('You do not have access to this page.');
        window.location.href = '../$role_redirect';
    </script>";
    exit();
}


=======
}


if ($_SESSION['role_name'] !== 'SUPERVISOR') {
    $role_redirect = strtolower($_SESSION['role_name']) . "/";
    echo "<script>
        alert('You do not have access to this page.');
        window.location.href = '../$role_redirect';
    </script>";
    exit();
}

>>>>>>> 963cf97d0c76debcafe1ed9557be3be99da14b2d
$name = $_SESSION['name'];
$supervisor_id = $_SESSION['user_id'];


$sql = "SELECT * FROM complaints WHERE supervisor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supervisor_id);
$stmt->execute();
$result = $stmt->get_result();

<<<<<<< HEAD
=======
// Check if supervisor is assigned to at least one active place
$check_sql = "SELECT `id` FROM `supervisor_map` WHERE `supervisor_id` = ? AND `is_active` = 1";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $supervisor_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo "<script>alert('You are not assigned to any place. Please contact the admin.');</script>";
    // Optional: Redirect or prevent further access
    // exit(); // Uncomment if you want to stop further access
}
$check_stmt->close();



>>>>>>> 963cf97d0c76debcafe1ed9557be3be99da14b2d
db_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Care - Supervisor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- dataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/nav.css">
    <style>
 
    </style>
</head>

<body>
<?php include './nav.php'; ?>
    <!-- Content Section -->
    <div class="container mt-5 pt-4 p-5">
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div data-type="Open" class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Open </h5>
                        <h2 class="text-warning" id="openCount"></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div data-type="In-Progress" class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">In Progress </h5>
                        <h2 class="text-success" id="inProgressCount"></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div data-type="Resolved" class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Resovled </h5>
                        <h2 class="text-info" id="resolvedCount"></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div data-type="All" class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total </h5>
                        <h2 class="text-primary" id="totalCount"></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updated Complaint Management Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Complaint Management</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="complaintsTable">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th>Complaint ID</th>
                                <th>Complaint Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- ajax call -->
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Priority Update Modal -->
        <div class="modal fade" id="updatePriorityModal" tabindex="-1" aria-labelledby="updatePriorityModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="updatePriorityModalLabel">Update Complaint Priority</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="complaintId" class="form-label">Complaint ID</label>
                                <input type="text" class="form-control" id="complaintId" value="1" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="prioritySelect" class="form-label">Select Priority</label>
                                <select class="form-select" id="prioritySelect">
                                    <option value="High" selected>High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Priority</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaint Details Modal -->
        <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel"
            aria-hidden="true">
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


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


        <script>
            $(document).ready(function() {
                $('#complaintsTable').DataTable();
            });
        </script>

        <!-- handle the dynamic modal population: -->

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Get all view buttons
                const viewButtons = document.querySelectorAll(".view-btn");

                // Add click event listener to each button
                viewButtons.forEach(button => {
                    button.addEventListener("click", function() {
                        // Extract data attributes
                        const complaintId = button.getAttribute("data-id");
                        const issue = button.getAttribute("data-issue");
                        const status = button.getAttribute("data-status");
                        const filedOn = button.getAttribute("data-filed-on");
                        const image = button.getAttribute("data-image");

                        // Populate modal fields
                        document.getElementById("modalComplaintId").textContent = complaintId;
                        document.getElementById("modalIssue").textContent = issue;
                        document.getElementById("modalStatus").textContent = status;
                        document.getElementById("modalFiledOn").textContent = filedOn;
                        document.getElementById("modalImage").src = image;
                    });
                });
            });
        </script>

        <!-- handle form update submission: -->
        <script>
            function isEmpty(value) {
                return value === undefined || value === null || value === "" ||
                    (Array.isArray(value) && value.length === 0) ||
                    (typeof value === "object" && Object.keys(value).length === 0);
            }

            var status = "Open";

            $(document).ready(function() {

                // load count
                fetchStatusCounts();

                //Load on refresh or open
                fetchSupervisorComplaintByType(status);

                $(".card").click(function() {
                    var changeStatus = $(this).data("type");

                    if (!isEmpty(changeStatus) && changeStatus != status)
                        status = changeStatus;

                    console.log(status);
                    fetchSupervisorComplaintByType(status);
                });

            });

            function fetchSupervisorComplaintByType(status) {
                $.ajax({
                    url: "../query/fetch_spvr_cmpt_by_status.php",
                    type: "POST",
                    data: {
                        status: status
                    }, // Send type to backend
                    success: function(complaints) {
                        var tableBody = $("#complaintsTable tbody");
                        tableBody.empty(); // Clear existing rows

                        console.log(complaints);

                        if (complaints.length > 0) {
                            $.each(complaints, function(index, complaint) {

                                var action;
                                switch (complaint.complaint_status) {
                                    case "Open":
                                        action = `<div class="d-flex justify-content-center align-items-center gap-2">
                                                <button class="btn btn-warning btn-sm text-nowrap" onclick="confirmUpdate(${complaint.id}, 'In-Progress',${complaint.user_id},'${complaint.complaint_status}')">In Progress</button>
                                                <button class="btn btn-danger btn-sm" onclick="confirmUpdate(${complaint.id}, 'Rejected',${complaint.user_id},'${complaint.complaint_status}')">Reject</button>
                                                <button class="btn btn-primary btn-sm" onclick="viewComplaint(${complaint.id})">View</button>
                                            </div>`;
                                        break;
                                    case "In-Progress":
                                        action = `<div class="d-flex justify-content-center align-items-center gap-2">
                                                <button class="btn btn-success btn-sm text-nowrap" onclick="confirmUpdate(${complaint.id}, 'Resolved',${complaint.user_id},'${complaint.complaint_status}')">Resolved</button>
                                                <button class="btn btn-primary btn-sm" onclick="viewComplaint(${complaint.id})">View</button>
                                            </div>`;
                                        break;
                                    case "Resolved":
                                        action = `<div class="d-flex justify-content-center align-items-center gap-2">
                                                <button class="btn btn-primary btn-sm" onclick="viewComplaint(${complaint.id})">View</button>
                                            </div>`;
                                        break;
                                    default:
                                        action = `<div class="d-flex justify-content-center align-items-center gap-2">
                                                <button class="btn btn-primary btn-sm" onclick="viewComplaint(${complaint.id})">View</button>
                                            </div>`;
                                        break;

                                }
                                var row = `<tr>
                                                 <td>${index + 1}</td> 
                                                 <td>${complaint.id}</td>
                                                 <td>${complaint.complaint_type}</td>
                                                 <td>${complaint.title}</td>
                                                 <td>${complaint.description}</td>
                                                 <td>${complaint.created_at}</td>
                                                 <td>${complaint.complaint_status}</td>
                                                 <td>${action}</td>
                                                 </tr>`;
                                tableBody.append(row);
                            });

                        } else {
                            tableBody.append("<tr><td colspan='8'>No complaints found.</td></tr>");
                        }
                    }
                });
            }


            function confirmUpdate(id, new_status, user_id, current_status) {
                if (confirm(`Are you sure you want to change the status to "${new_status}"?`)) {
                    updateStatus(id, new_status, user_id, current_status);
                }
            }

            function updateStatus(id, new_status, user_id, current_status) {
                $.post("./update_complaint.php", {
                    id: id,
                    status: new_status,
                    user_id: user_id
                }, function(response) {
                    fetchSupervisorComplaintByType(current_status);
                    fetchStatusCounts()
                    alert(response); // Show success or error message
                });
            }

            function viewComplaint(id) {
                window.location = "./view_complaint.php?id=" + id;
                // alert("Viewing details for Complaint ID: " + id);
            }



            
            function fetchStatusCounts() {
                $.ajax({
                    url: "../query/complaints_status_count.php",
                    data: {
                        supervisor_id: <?php echo $supervisor_id ?>
                    },
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(" fetchStatusCounts : ", response);

                        $("#openCount").text(response["Open"]);
                        $("#inProgressCount").text(response["In-Progress"]);
                        $("#resolvedCount").text(response["Resolved"]);
                        $("#rejectedCount").text(response["Rejected"]);
                        $("#totalCount").text(
                            (parseInt(response["Open"]) || 0) +
                            (parseInt(response["In-Progress"]) || 0) +
                            (parseInt(response["Resolved"]) || 0) +
                            (parseInt(response["Rejected"]) || 0)
                        );

                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching status count:", error);
                    }
                });
            }
            // Fetch status counts every 5 seconds
            setInterval(fetchStatusCounts, 10000);
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>