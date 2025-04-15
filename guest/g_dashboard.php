<?php
ob_start();
require_once "../classes/User.php";
require_once "../classes/Guest.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];
$guest_name = $user->getName();
?>
<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Guest Dashboard - Sunrise Hotel</title>

  <!--Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { height: 100%; background-color: #f8f9fa; }

    .navbar {
      background-color: #003366 !important;
    }
    .navbar-brand {
      font-weight: bold;
    }

    .hero {
      height: 100vh;
      background: url('https://source.unsplash.com/1600x900/?luxury-hotel') no-repeat center center/cover;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      font-size: 2rem;
      font-weight: bold;
      position: relative;
      animation: fadeIn 2s ease-in-out;
    }
    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.4);
    }
    .hero .container {
      position: relative;
      z-index: 1;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .room-card img {
      height: 200px;
      object-fit: cover;
    }
    .room-card {
      border: 2px solid #D4AF37;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .room-card:hover {
      transform: scale(1.08);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-primary {
      background-color: #003366;
      border-color: #003366;
    }
    .btn-primary:hover {
      background-color: #002244;
      border-color: #002244;
    }

    .nav-link.logout {
      color: #ff6b6b !important;
    }
    .nav-link.logout:hover {
      color: #ff5252 !important;
    }
  </style>
</head>
<body>

<!--Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">Sunrise Hotel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        
        <li class="nav-item">
          <a class="nav-link" href="g_profile.php"><i class="fas fa-user"></i> Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="g_room_service.php">
            <i class="fas fa-bell-concierge"></i> Room Service
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="g_room_service_status.php">
            <i class="fas fa-list-check"></i> My Service Status
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="g_booking.php"><i class="fas fa-calendar-check"></i> Bookings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="g_payments.php"><i class="fas fa-credit-card"></i> Payments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link logout" href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<div class="hero">
  <div class="container">
    <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($guest_name); ?>!</h2>
    <h1>Experience Luxury & Comfort</h1>
    <p>Book your stay with ease and enjoy premium services.</p>
    <a href="#rooms" class="btn btn-lg btn-warning">Explore Rooms</a>
  </div>
</div>

<!--  Room Preview -->
<section id="rooms" class="container my-5">
  <h2 class="text-center mb-4">Our Rooms</h2>
  <div class="row">
    <div class="col-md-4">
      <div class="card room-card">
        <img src="../image/s1.jpg" class="card-img-top" alt="Room">
        <div class="card-body text-center">
          <h5 class="card-title">Standard Room</h5>
          <p class="card-text">Enjoy a comfortable stay with all modern amenities.</p>
          <a href="g_sdetails.php" class="btn btn-primary">View Details</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card room-card">
        <img src="../image/d1.jpg" class="card-img-top" alt="Room">
        <div class="card-body text-center">
          <h5 class="card-title">Deluxe Room</h5>
          <p class="card-text">Spacious and elegant with premium facilities.</p>
          <a href="g_ddetails.php" class="btn btn-primary">View Details</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card room-card">
        <img src="../image/l1.jpg" class="card-img-top" alt="Room">
        <div class="card-body text-center">
          <h5 class="card-title">Luxury Suite</h5>
          <p class="card-text">The ultimate experience of comfort and opulence.</p>
          <a href="g_ldetails.php" class="btn btn-primary">View Details</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!--  Footer -->
<footer style="background: #003366; color: white; padding: 10px; text-align: center; margin-top: 40px;">
  <p>&copy; 2025 Sunrise Hotel. All Rights Reserved.</p>
</footer>

<!--  Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelector('.logout')?.addEventListener('click', function(e) {
    if (!confirm('Are you sure you want to logout?')) {
      e.preventDefault();
    }
  });
</script>
</body>
</html>
