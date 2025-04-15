<?php

ob_start();

require_once '../classes/User.php';
require_once '../classes/Admin.php';
require_once '../classes/Employee.php';
require_once '../classes/Guest.php';
require_once '../role_factory.php';

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];

// Redirect if not employee
if (!method_exists($user, 'getRole') || $user->getRole() !== 'employee') {
    header("Location: ../login.php");
    exit();
}


ob_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Dashboard - HMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap, Fonts, Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- Styles -->
  <style>
    
    html, body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }

    .navbar {
      background-color: #003366 !important;
      position: sticky;
      top: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 12px;
    }

    .dashboard-card {
      border: none;
      border-radius: 16px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      height: 100%;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
    }

    .card-body h4 {
      font-weight: 600;
    }

    .dashboard-container {
      padding-top: 60px;
      padding-bottom: 60px;
    }

    .nav-link.logout {
      color: #ff6b6b !important;
    }

    .nav-link.logout:hover {
      color: #ff5252 !important;
    }

    footer {
      background: #003366;
      color: white;
      padding: 12px;
      text-align: center;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<!--  NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#" style="font-size: 20px;">Sunrise Hotel - Employee Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link logout" href="e_logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!--  DASHBOARD CONTENT -->
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 74vh;">
  <div class="container dashboard-container">
    <h2 class="text-center mb-5">
      Welcome, <?= htmlspecialchars($user->getName()); ?>
    </h2>
    
    <div class="row g-4">
      <!-- My Tasks -->
      <div class="col-md-4">
        <a href="mytask.php" class="text-decoration-none">
          <div class="card text-white bg-info dashboard-card">
            <div class="card-body text-center">
              <h4><i class="fas fa-tasks me-2"></i>My Tasks</h4>
              <p>View tasks and your schedule</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Room Service -->
      <div class="col-md-4">
        <a href="room_service.php" class="text-decoration-none">
          <div class="card text-white bg-success dashboard-card">
            <div class="card-body text-center">
              <h4><i class="fas fa-concierge-bell me-2"></i>Room Service</h4>
              <p>Manage guest requests</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Booking -->
      <div class="col-md-4">
        <a href="checkbooking.php" class="text-decoration-none">
          <div class="card text-white dashboard-card" style="background-color: #6f42c1;">
            <div class="card-body text-center">
              <h4><i class="fas fa-calendar-alt me-2"></i>Booking</h4>
              <p>Check all the bookings</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Profile -->
      <div class="col-md-4">
        <a href="e_myprofile.php" class="text-decoration-none">
          <div class="card text-white bg-primary dashboard-card">
            <div class="card-body text-center">
              <h4><i class="fas fa-user me-2"></i>My Profile</h4>
              <p>Update your information</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<!--  FOOTER -->
<footer>
  <p>&copy; 2025 Sunrise Hotel. All Rights Reserved.</p>
</footer>

<!--  JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  //  Logout confirmation
  document.querySelector('.logout')?.addEventListener('click', function(e) {
    if (!confirm('Are you sure you want to logout?')) {
      e.preventDefault();
    }
  });
</script>
</body>
</html>
