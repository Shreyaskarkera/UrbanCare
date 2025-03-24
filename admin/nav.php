

  <div class="sidebar fixed-right" id="sidebar ">
      <h2 class="text-center fw-bold ">Urban Care</h2>
      <ul class="nav flex-column ">
          <li class="nav-item">
              <a class="nav-link text-white fs-6" href="./index.php"><i class="bi bi-house-door me-2"></i>Dashboard</a>
          </li>
          
          <li class="nav-item">
              <a class="nav-link text-white fs-6" href="./supervisor.php"><i class="bi bi-people me-2"></i>Supervisors</a>
          </li>
          <li class="nav-item">
              <a class="nav-link text-white fs-6" href="./users.php"><i class="fa-solid fa-address-book me-2"></i>Manage User</a>
          </li>
          <li class="nav-item">
              <a class="nav-link text-white fs-6" href="./location.php"><i class="bi bi-geo-alt me-2"></i>Location</a>
          </li>
          <li class="nav-item">
              <a class="nav-link text-white fs-6" href="./Reports.php"><i class="bi bi-file-earmark-text me-2"></i>Reports</a>
          </li>
          <li class="nav-item">
              <a class="nav-link text-white fs-6" href="./allocate_supervisor.php"><i class="fa-solid fa-address-book me-2"></i>Allocate Supervisor</a>
          </li>
          <li class="nav-item">
              <a class="nav-link text-white fs-6" href="./view_feedback.php"><i class="fa-solid fa-address-book me-2"></i>View Feedback</a>
          </li>
          <li class="nav-item">
              <a class="nav-link text-white fs-6" href="../logout.php"
                  onclick="return confirm('Are you sure you want to logout?');">
                  <i class="bi bi-box-arrow-right me-2"></i>Logout
              </a>
          </li>
      </ul>
  </div>

  <!-- Top Navigation -->
  <div class="top-nav d-flex justify-content-between align-items-center p-2 fixed-top shadow">
      <h3 class="heading  m-0"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h3>
      <div class="user-info d-flex align-items-center">
          <img src="profile.jpg" alt="Profile Picture" class="rounded-circle" width="40" height="40">
          <span class="ms-2 "><?php echo $name; ?></span>
      </div>
  </div>

  <!-- Add padding to main content so it doesn’t overlap with the fixed navbar -->
  <style>

  </style>