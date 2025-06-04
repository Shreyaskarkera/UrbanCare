
<div class="sidebar fixed-right" id="sidebar">
    <h2 class="text-center fw-bold">Urban Care</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="./index.php">
                <i class="fa-solid fa-house me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="./supervisor.php">
                <i class="fa-solid fa-users me-2"></i>Supervisors
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="./users.php">
                <i class="fa-solid fa-user-cog me-2"></i>Manage User
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="./location.php">
                <i class="fa-solid fa-map-marker-alt me-2"></i>Location
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="./Reports.php">
                <i class="fa-solid fa-file-alt me-2"></i>Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="./allocate_supervisor.php">
                <i class="fa-solid fa-user-check me-2"></i>Allocate Supervisor
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="./view_feedback.php">
                <i class="fa-solid fa-comment-dots me-2"></i>View Feedback
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="./complaint_type.php">
                <i class="fa-solid fa-clipboard-list me-2"></i>Complaint Type
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white fs-6" href="../logout.php"
                onclick="return confirm('Are you sure you want to logout?');">
                <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
            </a>
        </li>
    </ul>
</div>


  <!-- Top Navigation -->
  <div class="top-nav d-flex justify-content-between align-items-center p-2 fixed-top shadow">
      <h3 class="heading  m-0"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h3>
      <div class="user-info d-flex align-items-center">
          <span class="ms-2 "><?php echo $name; ?></span>
      </div>
  </div>

  <!-- Add padding to main content so it doesn’t overlap with the fixed navbar -->
  <style>

  </style>