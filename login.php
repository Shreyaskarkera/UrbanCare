<?php
require_once 'authentication.php';

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        die("All fields are required.");
    }

    $user = getUserByEmailPassword($email, $password);
    if ($user) {
        session_start();
        // Fetch the user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role_id'] = $user['role_id'];



        $role = getRoleById($_SESSION['role_id']);

        if (empty($role)) {

            // Send alert message and then redirect
            echo "<script>
    alert('Failed to Login');
    window.location.href = 'login.php';
</script>";
        }

        $_SESSION['role_name'] = strtoupper($role['name']);

        switch (strtoupper($role['name'])) {
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
                die("Please contact admin");
        }

        // header("Location: user/user_home.php?user=" . $_SESSION['id']);  // Redirect to a dashboard or another page
        exit();
    } else {
        echo "<script>
             alert('Invalid email or password!');window.location.href = 'login.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="text-center">Login</h3>
                <form action="" method="POST" class="border border-1 p-3 rounded shadow-sm p-3 mb-2 bg-body rounded">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" required>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>