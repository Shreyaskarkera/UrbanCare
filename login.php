<?php
require_once 'authentication.php';

session_start();

$errorMessage = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href='login.php';</script>";
        exit();
    }

    $user = getUserByEmailPassword($email, $password, $errorMessage);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role_id'] = $user['role_id'];

        $role = getRoleById($_SESSION['role_id']);

        if (!$role) {
            echo "<script>alert('Failed to retrieve role. Contact admin.'); window.location.href='login.php';</script>";
            exit();
        }

        $_SESSION['role_name'] = strtoupper($role['name']);

        switch ($_SESSION['role_name']) {
            case 'USER':
                header("Location: user/");
                break;
            case 'SUPERVISOR':
                header("Location: supervisor/");
                break;
            case 'ADMIN':
                header("Location: admin/");
                break;
            default:
                echo "<script>alert('Invalid role. Contact admin.'); window.location.href='login.php';</script>";
                exit();
        }
    } else {
        echo "<script>alert('$errorMessage'); window.location.href='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(240, 240, 240);
           
        }
        .login-container {
            margin-top: 50px;
        }
        .login-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <h3 class="text-center">Login</h3>
                <form action="" method="POST" class="login-form">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center mt-3">Don't have an account? <a href="user/sign_up.html">Sign up here</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
