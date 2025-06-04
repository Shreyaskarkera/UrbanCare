
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <h1>Urban Care</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link fs-6" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link fs-6" href="./complaint_raise.php">Raise Complaint</a></li>
                    <li class="nav-item"><a class="nav-link fs-6" href="./view_complaints.php">View Complaints</a></li>
                    <li class="nav-item"><a class="nav-link fs-6" href="./user_feedback.php">Feedback</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative " href="./user_notification.php">
                            <i class="bi bi-bell fs-5"></i>
                            <span id="notificationBadge"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger ">
                                0
                            </span>

                        </a>
                    </li>
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle fs-5" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo $name; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="./view_profile.php">View Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>