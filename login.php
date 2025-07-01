<?php
session_start();
require_once 'authentication.php';

// ✅ Redirect if user tries to access login.php/something/else
if ($_SERVER['PHP_SELF'] !== '/UrbanCare/login.php') {
    header("Location: /UrbanCare/login.php");
    exit();
}

// ✅ Prevent already logged-in users from accessing login.php
if (isset($_SESSION['user_id']) && isset($_SESSION['role_name'])) {
    $role = strtolower($_SESSION['role_name']);
    header("Location: $role/");
    exit();
}

// ✅ Clear redirect_back on manual visit to login
unset($_SESSION['redirect_back']);

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
        // ✅ Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role_id'] = $user['role_id'];

        // ✅ Set role name
        $role = getRoleById($user['role_id']);
        if (!$role) {
            echo "<script>alert('Failed to retrieve role. Contact admin.'); window.location.href='login.php';</script>";
            exit();
        }

        $_SESSION['role_name'] = strtoupper($role['name']);
        $roleFolder = strtolower($_SESSION['role_name']);

        // ✅ Priority 1: Redirect to saved redirect if it matches role
        if (!empty($_SESSION['redirect_back'])) {
            $redirectURL = $_SESSION['redirect_back'];
            unset($_SESSION['redirect_back']);

            if ((strpos($redirectURL, "/$roleFolder/") === 0 || strpos($redirectURL, "$roleFolder/") === 0)) {
                header("Location: $redirectURL");
                exit();
            }
        }

        // ✅ Priority 2: Redirect to role dashboard
        header("Location: $roleFolder/");
        exit();
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
            background: #E8F5E9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #FFFFFF;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            width: 450px;
        }
        .login-form label {
            color: #2E7D32;
            font-size: 1.2rem;
        }
        .login-form input {
            background: #FAFAFA;
            border: 1px solid #CCCCCC;
            color: #333;
            font-size: 1.1rem;
            padding: 10px;
        }
        .btn-custom {
            background: #4CAF50;
            color: #fff;
            border: none;
            font-size: 1.2rem;
            padding: 12px;
            border-radius: 8px;
        }
        .btn-custom:hover {
            background: #388E3C;
        }
        a {
            color: #388E3C;
            font-size: 1.1rem;
        }
        a:hover {
            color: #2E7D32;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Login</h2>
        <form action="" method="POST" class="login-form">
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-custom w-100">Login</button>
        </form>
        <p class="text-center mt-4">Don't have an account? <a href="user/sign_up.html">Sign up here</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
