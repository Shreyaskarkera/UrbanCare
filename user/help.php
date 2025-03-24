<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Guide - Urban Care</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 900px;
        }
        .section {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><h1>Urban Care</h1></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#carouselExampleControlsNoTouching">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./complaint_raise.php">Raise Complaint</a></li>
                    <li class="nav-item"><a class="nav-link" href="./view_complaints.php">View Complaints</a></li>
                    <li class="nav-item"><a class="nav-link" href="help.html">Help</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="./user_notification.php">
                            <i class="bi bi-bell"></i>
                            <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    0
</span>

                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo $name; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="./view_profile.php">View Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


<div class="container mt-5">
    <h2 class="text-center mb-4">Help & User Guide</h2>

    <div class="section bg-light">
        <h4>📌 How to Register & Login?</h4>
        <ul>
            <li>Click on the <strong>"Register"</strong> button on the homepage.</li>
            <li>Fill in your details and submit the form.</li>
            <li>After registration, go to the <strong>"Login"</strong> page and enter your credentials.</li>
            <li>Click <strong>"Login"</strong> to access your dashboard.</li>
        </ul>
    </div>

    <div class="section bg-light">
        <h4>🗑️ How to File a Complaint?</h4>
        <ul>
            <li>Go to the <strong>"Raise Complaint"</strong> section.</li>
            <li>Enter details like <strong>title, description, and location</strong>.</li>
            <li>Upload a relevant <strong>image</strong> (optional but recommended).</li>
            <li>Click <strong>"Submit"</strong> to register your complaint.</li>
        </ul>
    </div>

    <div class="section bg-light">
        <h4>👀 How to Track Your Complaint?</h4>
        <ul>
            <li>Navigate to the <strong>"View Complaints"</strong> section.</li>
            <li>Find your complaint in the list and click <strong>"View"</strong>.</li>
            <li>You can see the <strong>status, location, and action taken</strong>.</li>
        </ul>
    </div>

    <div class="section bg-light">
        <h4>❓ Need Further Assistance?</h4>
        <p>If you have any issues, please contact our support team at:</p>
        <p><strong>Email:</strong> support@urbancare.com</p>
        <p><strong>Phone:</strong> +91 98765 43210</p>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</div>

</body>
</html>
