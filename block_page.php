<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .browser-frame {
            width: 90%;
            max-width: 1000px;
            background: white;
            border-radius: 15px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 50px;
        }

        .browser-header {
            height: 50px;
            background: #ddd;
            border-radius: 15px 15px 0 0;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .dot {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .red {
            background: #ff5f56;
        }

        .yellow {
            background: #ffbd2e;
        }

        .green {
            background: #27c93f;
        }

        .error-icon {
            font-size: 120px;
            color: #d9534f;
            margin-top: 20px;
        }

        h1 {
            font-size: 2.5rem;
            margin-top: 20px;
        }

        .explanation {
            margin-top: 30px;
            text-align: left;
        }

        .explanation h4 {
            font-size: 1.5rem;
        }

        .explanation p {
            font-size: 1.1rem;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="browser-frame">
        <div class="browser-header">
            <div class="dot red"></div>
            <div class="dot yellow"></div>
            <div class="dot green"></div>
        </div>
        <h1 class="mt-4 text-danger">Sorry, you have been blocked</h1>
        <p class="text-muted fs-5">You are unable to access this website</p>
        <i class="bi bi-x-circle-fill error-icon"></i>

        <div class="row explanation">
            <div class="col-md-6">
                <h4>Why have I been blocked?</h4>
                <p>
                    Your account may have been blocked due to security issues or policy violations.
                    If you think this is a mistake, check your account status.
                </p>
            </div>
            <div class="col-md-6">
                <h4>What can I do to resolve this?</h4>
                <p>
                    Contact support for further help. Please include your registered email and any
                    relevant details when reaching out.
                </p>
<<<<<<< HEAD
                <a href="./index.html" class="btn btn-lg btn-primary mt-3">
=======
                <a href="../UrbanCare/index.php" class="btn btn-lg btn-primary mt-3">
>>>>>>> 963cf97d0c76debcafe1ed9557be3be99da14b2d
                    <i class="bi bi-house-door-fill"></i> Go to Homepage
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>