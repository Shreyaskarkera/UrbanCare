
<?php
session_start(); 

// Check if session is set and the role is USER
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    // Redirect to index page if the session does not exist or role is not USER
    header("Location: ../index.html");
    exit();
} 

if($_SESSION['role_name'] != 'ADMIN'){
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
  <title>Admin Dashboard Overview</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
    }
    header {
      background-color: #4CAF50;
    }
    header h1 {
      margin: 0;
    }
    header nav a {
      color: white;
      margin-left: 1rem;
      text-decoration: none;
    }
    .container {
      padding: 2rem;
    }
    .overview {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
    }
    .card {
      background-color: white;
      border-radius: 8px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    .card h2 {
      font-size: 2rem;
      margin: 0.5rem 0;
      color: #333;
    }
    .card p {
      color: #666;
    }
    .card i {
      font-size: 2rem;
      color: #4CAF50;
      margin-bottom: 0.5rem;
    }
  </style>
</head>
<body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Tasks</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Reports</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <div class="container">
    <h2>Dashboard Overview</h2>
    <div class="row">
      <div class="col-md-3 col-lg-3 col-sm-6 mb-4">
        <div class="card text-center">
          <i class="fas fa-tasks fa-3x"></i>
          <h2>120</h2>
          <p>Total Complaints</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center">
          <i class="fas fa-sync-alt fa-3x"></i>
          <h2>45</h2>
          <p>In Progress</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center">
          <i class="fas fa-check-circle fa-3x"></i>
          <h2>65</h2>
          <p>Resolved</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center">
          <i class="fas fa-user fa-3x"></i>
          <h2>10</h2>
          <p>Supervisors</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
