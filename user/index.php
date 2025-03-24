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

</head>

<body>

    <?php include './nav.php'; ?>

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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <?php include './footer.php'; ?>
    
</body>

</html>