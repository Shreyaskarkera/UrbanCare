<?php
session_start(); 

// Check if session is set and the role is USER
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    // Redirect to index page if the session does not exist or role is not USER
    header("Location: ../index.html");
    exit();
} 

if($_SESSION['role_name'] != 'USER'){
    echo "<script>
    alert('Dont have access to this page');window.location.href = '../login.php';
   </script>";
}



$name = $_SESSION['name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Urban Care</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Custom Color Scheme */
      /* Custom Color Scheme */
:root {
    --primary-color: #0077B6; /* Soft Blue */
    --secondary-color: #9e9e9e;
    --toggle-icon-secondary-color: #ffffff00; /* Vibrant Green */
    --accent-color: #FF6F61; /* Warm Coral */
    --background-color: #F1F1F1; /* Soft Gray */
    --text-color: #333333; /* Charcoal Gray */
    --footer-color: #2A3D66; /* Deep Slate */
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
    color:rgb(73, 73, 73) !important;
}

/* Custom Toggle Icon */
.navbar-toggler-icon {
    background-color: var(--toggle-icon-secondary-color); /* Change the toggle icon color */
}

.navbar-toggler-icon:hover {
    border: 1px solid var(--secondary-color); /* Optional: Add border for better visibility */
}

.navbar-toggler-icon:hover {
    background-color: var(--toggle-icon-secondary-color); /* Hover effect */
}

.navbar-toggler-icon:focus {
    box-shadow: none; /* Removes the blue shadow when focused */
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

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><h1>Urban Care</h1></a>
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

    <!-- Carousel -->
    <div id="carousel" class="carousel slide" data-bs-touch="false" data-bs-interval="3000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0YVNUBz-p5kLgjdmEAu84p6O2MMI2ONWyrw&s"
                    class="d-block w-100" alt="City Cleaning Effort">
            </div>
            <div class="carousel-item">
                <img src="https://media.istockphoto.com/id/517188688/photo/mountain-landscape.jpg?s=1024x1024&w=is&k=20&c=MB1-O5fjps0hVPd97fMIiEaisPMEn4XqVvQoJFKLRrQ=" 
                    class="d-block w-100" alt="Mountain View">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Recent Activities Section -->
    <!-- <section class="container my-5">
        <h3 class="text-center">Recent Activities</h3>
        <ul class="list-group mt-3">
            <li class="list-group-item">Filed a complaint for garbage overflow - <small>2025-01-15</small></li>
            <li class="list-group-item">Complaint resolved for illegal dumping - <small>2025-01-10</small></li>
            <li class="list-group-item">Feedback submitted for city cleaning drive - <small>2025-01-08</small></li>
        </ul>
    </section> -->

    <!-- Success Stories Section -->
    <section class="container my-5">
        <h3 class="text-center">Success Stories</h3>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card">
                    <img src="cleaning img.jpeg" class="card-img-top" alt="Before Cleanup">
                    <div class="card-body">
                        <h5 class="card-title">Before Cleanup</h5>
                        <p class="card-text">A street filled with garbage before intervention.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <img src="cleaning img2.jpeg" class="card-img-top" alt="After Cleanup">
                    <div class="card-body">
                        <h5 class="card-title">After Cleanup</h5>
                        <p class="card-text">The same street cleaned and well-maintained.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section id="faq" class="container my-5">
        <h3 class="text-center">Frequently Asked Questions</h3>
        <div class="accordion mt-3" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        How do I file a complaint?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne">
                    <div class="accordion-body">
                        Navigate to the "Complaints" section and fill out the form with all required details and an image.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        How long does it take to resolve a complaint?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo">
                    <div class="accordion-body">
                        The resolution time varies depending on the complexity of the issue, but most complaints are resolved within a week.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-white mt-5 p-4 text-center">
        &copy; 2025 Urban Care. All rights reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </script>
</body>
</html>
