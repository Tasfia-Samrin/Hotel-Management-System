<?php
// 1. Load all class definitions 
require_once '../classes/User.php';
require_once '../classes/Admin.php';
require_once '../classes/Employee.php';
require_once '../classes/Guest.php';
require_once '../role_factory.php'; 

//  Start session 
session_start();

//  Retrieve user object from session
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];

// Check if user is an admin
if (!method_exists($user, 'getRole') || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit();
}

//  The user is identified as admin. Continue to admin dashboard...
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - HMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
   .navbar {
    background-color: #003366 !important;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 12px;
   }
    body {
    margin: 0;
    padding-top: 56px; 
    }
    .dashboard-card {
      border: none;
      border-radius: 16px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);

    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
    }

    .card-body h4 {
      font-weight: 600;
    }

    .container h2 {
      font-weight: 600;
    }

    .dashboard-container {
      padding-top: 60px;
      padding-bottom: 60px;
    }
        /* Logout link styling */
    .nav-link.logout {
        color: #ff6b6b !important;
    }
    .nav-link.logout:hover {
        color: #ff5252 !important;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#" style="font-size: 25px;">Sunrise Hotel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="a_profile.php"><i class="fas fa-user"></i>My Profile</a></li>
                    <li class="nav-item">
                <a class="nav-link logout" href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
                    <i class="fas fa-sign-out-alt"></i> Logout
                         </a>
                  </li>
                </ul>
            </div>
</div>
  </nav>

  <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 74vh;">
  <div class="container dashboard-container text-center">
  <h2 class="text-center mb-5">Welcome, <?php echo htmlspecialchars($_SESSION['user']->getName()); ?></h2>
    <div class="row g-4">
      <div class="col-md-4">
        <a href="manageroom.php" class="text-decoration-none">
          <div class="card text-white bg-primary dashboard-card">
            <div class="card-body text-center">
              <h4>Manage Rooms</h4>
              <p>Add, Edit, Delete Room Info</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./booking/a-booking.php" class="text-decoration-none">
          <div class="card text-white bg-success dashboard-card">
            <div class="card-body text-center">
              <h4>Booking Info</h4>
              <p>Track Room Status & Bookings</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./manage_employee/m_e.php" class="text-decoration-none">
          <div class="card text-dark bg-warning dashboard-card">
            <div class="card-body text-center">
              <h4>Manage Employees</h4>
              <p>View/Add/Update Staff</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<footer style="background: #003366; color: white; padding: 12px; text-align: center; margin-top: 40px;">
  <p>&copy; 2025 Sunrise Hotel. All Rights Reserved.</p>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple confirmation for logout
        document.querySelector('.logout').addEventListener('click', function(e) {
            if(!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>


