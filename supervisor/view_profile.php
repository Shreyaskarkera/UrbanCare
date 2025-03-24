<?php
session_start();
include '../connection.php'; // Ensure this file connects to the database
$conn = db_connect();

// Check if supervisor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2 || $_SESSION['role_name'] != 'SUPERVISOR') {
    echo "<script>
    alert('You do not have access to this page.'); window.location.href = '../login.php';
    </script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Fetch supervisor details from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

// Default profile image
$profile_image = !empty($user['photo']) ? "../" . htmlspecialchars($user['photo']) : '../default_profile_picture.jpg';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .profile-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-card {
            display: flex;
            flex-direction: row;
            background:whitesmoke;
            color:rgb(0, 0, 0);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            align-items: center;
        }

        .profile-img-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-img {
            width: 250px;
            height: 240px;
            border-radius: 10px;
            border: 4px solid #ffffff;
        }

        .profile-details {
            flex: 2;
            padding-left: 20px;
        }

        .profile-details h4 {
            margin-bottom: 15px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .profile-details table {
            width: 100%;
           
        }

        .profile-details th {
            text-align: left;
            width: 30%;
            border-radius: 10px;
            /* background-color: whitesmoke */
        }

        .profile-details td {
            text-align: left;
            border-radius: 10px;
            border-spacing: 15px;
            /* background-color: whitesmoke */
        }

        .btn-primary {
            background: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        @media (max-width: 768px) {
            .profile-card {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            .profile-img-container {
                margin-bottom: 15px;
            }

            .profile-details {
                padding-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container profile-container">
        <div class="profile-card">
            <div class="profile-img-container">
                <img src="<?php echo $profile_image; ?>" class="img-thumbnail profile-img" alt="Profile Picture">
            </div>
            <div class="profile-details">
                <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                <table class="table table-borderless text-white g-3">
                    <tr>
                        <th >ID</th>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo htmlspecialchars($user['phone_no']); ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Joined Date</th>
                        <td><?php echo htmlspecialchars($user['joined_date'] ?? 'N/A'); ?></td>
                    </tr>
                </table>
                <a href="update_supervisor_profile.php" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
