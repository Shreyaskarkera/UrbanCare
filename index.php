<?php
session_start();

// If already logged in, redirect to respective dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['role_name'])) {
    $role = strtolower($_SESSION['role_name']);
    header("Location: $role/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
          /* Navbar Styling */
          body{
            background-color: #E8F5E9;
          }
          .navbar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background-color: rgba(0, 77, 64, 0.6) !important;
    backdrop-filter: blur(8px);
    padding: 15px 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 1000;
}

.navbar-brand h1 {
    font-size: 2rem;
    font-weight: bold;
    color: #ffffff;
}

.navbar-nav .nav-link {
    color: #e0f2f1 !important; /* Lighter green for readability on transparent bg */
    margin-right: 15px;
    transition: color 0.3s;
    font-size: 19px;
}

.navbar-nav .nav-link:hover {
    color: #b2dfdb !important;
}

/* Login and Sign Up Buttons */
.navbar-nav .nav-link i {
    margin-right: 5px;
}

.navbar-nav .btn {
    background-color: #00796b;
    color: #ffffff !important;
    border-radius: 5px;
    padding: 5px 12px;
    transition: background 0.3s;
    border: none;
}

.navbar-nav .btn:hover {
    background-color: #004d40;
}

#carousel {
    margin-top: 0;
    max-height: 100vh;
    overflow: hidden;
    margin-bottom: 0;
}
.carousel-image-wrapper {
    position: relative;
    height: 100vh; /* Increased height */
    overflow: hidden;
}

.carousel-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(40%) contrast(1.1); /* Slightly darker with more contrast */
}

.carousel-caption {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    text-align: center;
    z-index: 10;
    /* Remove width: 80% (or adjust if needed) */
    white-space: nowrap; /* Prevents wrapping if needed */
}

.carousel-caption h2,
.carousel-caption p {
    display: inline-block; /* Forces inline behavior */
    margin: 0 auto; /* Helps with centering */
}

.carousel-caption h2 {
    font-size: 2.8rem;
    font-weight: 700;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
    margin-bottom: 1rem;
}

.carousel-caption p {
    font-size: 1.2rem;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
    max-width: 800px; /* Optional: Limits paragraph width */
    white-space: normal; /* Allows text to wrap */
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .carousel-caption h2 {
        font-size: 2.2rem;
    }
    .carousel-caption p {
        font-size: 1.1rem;
    }
    #carousel, .carousel-image-wrapper {
        max-height: 60vh;
    }
}

@media (max-width: 768px) {
    .carousel-caption h2 {
        font-size: 1.8rem;
    }
    .carousel-caption p {
        font-size: 1rem;
    }
    #carousel, .carousel-image-wrapper {
        max-height: 50vh;
    }
}

@media (max-width: 576px) {
    .carousel-caption h2 {
        font-size: 1.5rem;
    }
    .carousel-caption p {
        font-size: 0.9rem;
    }
    #carousel, .carousel-image-wrapper {
        max-height: 45vh;
    }
}
  /* Sections */
  section {
            padding: 60px 0;
        }

        #home {
            background-color: #E8F5E9; /* Light Green Background */
            color: #2E7D32;
            margin-top: 0;
        }

        #about_us {
            background: linear-gradient(135deg, #1B5E20, #4CAF50);
            color: #ffffff;
            padding: 80px 0;
        }

        #about_us h2 {
            color: #C8E6C9;
        }

        #about_us p {
            font-size: 1.2rem;
            line-height: 1.6;
        }

        #about_us img {
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }
        #features,#contact{
            background-color: #E8F5E9;
        }

        #cta {
            background-color: #2E7D32;
            color: #ffffff;
            text-align: center;
            padding: 50px 20px;
        }

        #cta .btn {
            border: 2px solid white;
            margin: 10px;
        }
    
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark shadow-sm fixed-top">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">
                <h1 class="mb-0">Urban Care</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto fs-5">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about_us">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="bi bi-person-circle"></i> Login
                        </a>
                    </li>
                    <li class="nav-item ms-1">
                        <a class="nav-link btn fs-5" href="user/sign_up.html">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
<!-- Carousel -->
<div id="carousel" class="carousel slide mb-5" data-bs-touch="true" data-bs-interval="3000">
    <div class="carousel-inner">
        <!-- Slide 1: City Cleaning Effort -->
        <div class="carousel-item active">
            <div class="carousel-image-wrapper">
                <img src="./asset/carousel-image/worker.jpg" class="d-block w-100" alt="City Cleaning Effort">
                <div class="carousel-caption">
                    <div class="caption-title">
                        <h2>Together for a Cleaner Future</h2>
                    </div>
                    <div class="caption-text">
                        <p class="lead">Our dedicated workers strive every day to keep the streets clean and our city beautiful.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: User Holding Mobile (Citizen Reporting) -->
        <div class="carousel-item">
            <div class="carousel-image-wrapper">
                <img src="./asset/carousel-image/mobile.jpg" class="d-block w-100" alt="User Holding Mobile">
                <div class="carousel-caption">
                    <div class="caption-title">
                        <h2>Empowering Citizens</h2>
                    </div>
                    <div class="caption-text">
                        <p class="lead">With the power of technology, citizens can report issues in real-time to help keep our city clean.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3: Workers Cleaning City -->
        <div class="carousel-item">
            <div class="carousel-image-wrapper">
                <img src="./asset/carousel-image/worker clening.jpg" class="d-block w-100" alt="Workers Cleaning City">
                <div class="carousel-caption">
                    <div class="caption-title">
                        <h2>Hard Work, Cleaner Streets</h2>
                    </div>
                    <div class="caption-text">
                        <p class="lead">Our hardworking team of sanitation workers are always on the move, ensuring the city remains pristine.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 4: Clean City -->
        <div class="carousel-item">
            <div class="carousel-image-wrapper">
                <img src="./asset/carousel-image/city.jpg" class="d-block w-100" alt="Clean City">
                <div class="carousel-caption">
                    <div class="caption-title">
                        <h2>Building a Sustainable Future</h2>
                    </div>
                    <div class="caption-text">
                        <p class="lead">By working together, we can maintain a cleaner, safer, and more sustainable environment for all.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
    <!-- Learn Section -->
    <!-- Home Section -->
    <section id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="asset/images/headline.svg" class="img-fluid rounded shadow" alt="Clean City Initiative">
                </div>
                <div class="col-md-6 mt-3">
                    <h2 class="fw-bold">Keep Your City Clean</h2>
                    <p class="lead mt-3">Join the City Corporation and be part of the solution. Take action by reporting
                        cleanliness issues in your area and help maintain a better environment for all.</p>
                    <p>Your voice matters! Help us build a cleaner and greener community by reporting issues directly to
                        the City Corporation.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Us Section -->
    <section id="about_us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mt-3">
                    <h2 class="fw-bold">About Us</h2>
                    <p class="mt-3">The City Corporation platform is dedicated to creating a cleaner, healthier, and
                        more livable city for everyone. We empower citizens to take action by reporting cleanliness
                        issues directly to the authorities, ensuring a faster and more effective resolution process.</p>
                    <p>With our easy-to-use platform, you can file complaints about trash, track their status in
                        real-time, and get notified when they are resolved. Together, we can build a community that
                        cares for its environment and takes pride in its surroundings.</p>
                </div>
                <div class="col-md-6 mt-3">
                    <img src="asset/images/about_us.svg" class="img-fluid" alt="About Urban Care">
                </div>
            </div>
        </div>
    </section>


    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Why Choose City Corporation</h2>
            <div class="row ">
                <div class="col-md-4 text-center">
                    <i class="bi bi-pencil-square" style="font-size: 2rem;"></i>
                    <h4 class="mt-3">File Complaints Effortlessly</h4>
                    <p>Submit detailed reports of cleanliness issues, including photos and descriptions, in just a few
                        clicks.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                    <h4 class="mt-3">Track Complaint Status</h4>
                    <p>Monitor your complaint's progress in real-time and stay updated on its resolution.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                    <h4 class="mt-3">Quick Resolutions</h4>
                    <p>Experience faster response times as your complaints are sent directly to the appropriate
                        authorities.</p>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6 text-center">
                    <i class="bi bi-phone" style="font-size: 2rem;"></i>
                    <h4 class="mt-3">User-Friendly Interface</h4>
                    <p>Navigate through our simple and intuitive platform with ease, whether you're tech-savvy or a
                        first-time user.</p>
                </div>
                <div class="col-md-6 text-center">
                    <i class="bi bi-people" style="font-size: 2rem;"></i>
                    <h4 class="mt-3">Community Impact</h4>
                    <p>Be a part of a movement to keep the city clean and inspire others to take responsibility.</p>
                </div>
            </div>
        </div>
        </div>
    </section>
    <section id="cta">
        <div class="container">
            <h2>Take the First Step Toward a Cleaner City!</h2>
            <p class="lead">Join thousands of citizens committed to keeping our city clean and beautiful. Sign up now to
                start reporting issues in your area.</p>
            <a href="./user/sign_up.html" class="btn btn-outline-light btn-lg">Sign Up Now</a>
            <a href="./login.php" class="btn btn-outline-light btn-lg">Login</a>
        </div>
    </section>

    <section id="contact" class="text-dark py-5">
        <div class="container">
            <h2 class="text-center">Get in Touch with US</h2>
            <p class="mt-4 text-center">We’re here to help! Reach out with any questions, feedback, or suggestions, and
                we’ll respond as soon as possible.
            </p>
            <div class="row mt-4">
                <div class="col-md-4 text-center">
                    <i class="bi bi-envelope" style="font-size: 2rem;"></i>
                    <h5 class="mt-2">Email</h5>
                    <p>
                        <a href="mailto:support@urbancare.com"
                            class="text-decoration-none">support@urbancare.com</a><br>
                        <a href="mailto:feedbak@urbancare.com" class="text-decoration-none">feedbak@urbancare.com</a>
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-telephone" style="font-size: 2rem;"></i>
                    <h5 class="mt-2">Phone</h5>
                    <p>
                        +123 456 7890 <br>
                        +123 345 0987
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-geo-alt" style="font-size: 2rem;"></i>
                    <h5 class="mt-2">Address</h5>
                    <p>
                       123 City Street<br>
                       Mangalore,Karnataka,<br>
                       1345,India
                    </p>
                </div>
                <div class="text-center mt-4">
                    <h5 class="mt-2">Working Hours</h5>
                    <p>
                      Monday to Friday: 9:00 AM - 6:00 PM<br>
                      Saturday: 10:00 AM - 4:00 PM<br>
                      Sunday: Closed
                    </p>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>


    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <!-- Branding -->
                <div class="col-md-6 text-center text-md-start">
                    <h5>Urban Care</h5>
                    <p>Working together for a cleaner and greener city.</p>
                </div>

                <!-- Contact Info -->
                <div class="col-md-6 text-center text-md-end">
                    <h5>Contact Us</h5>
                    <p>Email: <a href="mailto:support@citycorp.com" class="text-white">support@citycorp.com</a></p>
                    <p>Phone: +123 456 7890</p>
                    <p>Address: 123 City Street, YourCity</p>
                </div>
            </div>
            <!-- Copyright -->
            <div class="text-center mt-3">
                <p class="mb-0">&copy; 2025 City Corporation. All Rights Reserved.</p>
            </div>
        </div>
    </footer>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
</body>

</html>