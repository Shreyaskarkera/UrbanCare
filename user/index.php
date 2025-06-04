<?php include './sessionValidate.php'; ?>
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

    <link rel="stylesheet" href="./css/custom.css">

    <!-- dataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<style>
 /* Center the carousel */
#carousel {
    max-width: 100%;
    width: 90vw;
    margin: 30px auto;
    margin-top: 100px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.3);
    position: relative;
}

/* Image styles */
#carousel .carousel-item img {
    height: 80vh;
    max-height: 800px;
    object-fit: cover;
    width: 100%;
    border-radius: 15px;
    filter: brightness(50%); /* Darken the image directly */
}

/* Center text over image */
.carousel-caption {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    text-align: center;
    color: #fff;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
}

/* Text styles */
.carousel-caption h2 {
    font-size: 2.8rem;
    font-weight: bold;
}

.carousel-caption p {
    font-size: 1.3rem;
}

/* Carousel arrows */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    padding: 20px;
    transition: 0.3s ease-in-out;
}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
    background-color: rgba(255, 255, 255, 0.8);
}

/* Responsive tweaks */
@media (max-width: 768px) {
    #carousel .carousel-item img {
        height: 250px;
    }

    .carousel-caption h2 {
        font-size: 1.6rem;
    }

    .carousel-caption p {
        font-size: 0.95rem;
    }
}


</style>
</head>

<body>

    <?php include './nav.php'; ?>

  <!-- Carousel -->
<div id="carousel" class="carousel slide" data-bs-touch="true" data-bs-interval="3000">
  <div class="carousel-inner">

    <!-- Slide 1 -->
    <div class="carousel-item active">
      <img src="../asset/carousel-image/city street.jpg" class="d-block w-100" alt="City Cleaning Effort">
      <div class="carousel-caption d-none d-md-block">
        <h2>Clean Streets, Happy Lives</h2>
        <p>Join hands to keep your neighborhood clean and green.</p>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-item">
      <img src="../asset/carousel-image/report2.jpg" class="d-block w-100" alt="Reporting Issue via App">
      <div class="carousel-caption d-none d-md-block">
        <h2>Report Easily, Instantly</h2>
        <p>Use our platform to raise complaints with just one click.</p>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-item">
      <img src="../asset/carousel-image/municipal worker.jpg" class="d-block w-100" alt="Supervisor Taking Action">
      <div class="carousel-caption d-none d-md-block">
        <h2>Swift Action, Real Results</h2>
        <p>Our supervisors ensure your issues are resolved quickly.</p>
      </div>
    </div>

    <!-- Slide 4 -->
    <div class="carousel-item">
      <img src="../asset/carousel-image/Beforeafter.jpg" class="d-block w-100" alt="Urban Transformation">
      <div class="carousel-caption d-none d-md-block">
        <h2>Transforming Cities Together</h2>
        <p>Your voice + Our action = A better tomorrow.</p>
      </div>
    </div>

  </div>

  <!-- Controls -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>

  <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
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

   <!-- How It Works Section -->
<!-- Why Choose Us Section -->
<section class="container my-5">
    <h3 class="text-center fw-bold">Why Choose Us?</h3>
    <p class="text-center text-muted">We ensure a seamless and efficient complaint resolution process.</p>

    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <i class="bi bi-lightning-charge fs-1 text-primary"></i>
                <h5 class="mt-3">Quick Response</h5>
                <p>Complaints are assigned instantly to the right supervisor.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <i class="bi bi-geo-alt fs-1 text-danger"></i>
                <h5 class="mt-3">Location-Based Support</h5>
                <p>We ensure complaints are handled by local supervisors for faster action.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <i class="bi bi-shield-check fs-1 text-success"></i>
                <h5 class="mt-3">Reliable & Secure</h5>
                <p>Your complaints are handled with transparency and accountability.</p>
            </div>
        </div>
    </div>
</section>



    <!-- FAQs Section -->
    <section id="faq" class="container my-5">
    <h3 class="text-center fw-bold">Frequently Asked Questions</h3>
    <div class="accordion mt-4" id="faqAccordion">

        <!-- Question 1 -->
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

        <!-- Question 2 -->
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

        <!-- Question 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Can I track the status of my complaint?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree">
                <div class="accordion-body">
                    Yes, you can check the status of your complaint in the "My Complaints" section after logging in.
                </div>
            </div>
        </div>

        <!-- Question 4 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    What should I do if my complaint is ignored?
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour">
                <div class="accordion-body">
                    If your complaint is not addressed within the expected time, you can escalate it to the administrator via the "Contact Support" section.
                </div>
            </div>
        </div>

       

    </div>
</section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  
    <?php include './footer.php'; ?>
</body>

</html>