<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Rooms - Sunrise Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar {
      background-color: #003366 !important;
      position: sticky;
      top: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    h2 {
      color: #D4AF37;
    }

    .amenities {
      text-align: center;
      margin-top: 40px;
    }

    .amenities .amenities-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 0;
      margin-top: 20px;
    }

    .amenities .amenity {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      border: 2px solid #D4AF37;
      padding: 10px 20px;
      border-radius: 10px;
      background: #fff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .amenities i {
      font-size: 24px;
      color: #D4AF37;
    }
  </style>
</head>
<body>

<!--  Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="contactus.php">Contact Us</a></li>
        <li class="nav-item"><a href="login.php" class="btn btn-outline-light mx-2">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<!--  Room Cards -->
<section class="container my-5">
  <h2 class="text-center mb-4">Available Rooms</h2>
  <div class="row">
    <div class="col-md-4">
      <div class="card room-card">
        <img src="./image/s1.jpg" class="card-img-top" alt="Standard Room">
        <div class="card-body text-center">
          <h5 class="card-title">Standard Room</h5>
          <p class="card-text">Comfortable stay with modern amenities.</p>
          <a href="sdetails.php" class="btn btn-primary">View Details</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card room-card">
        <img src="./image/d1.jpg" class="card-img-top" alt="Deluxe Room">
        <div class="card-body text-center">
          <h5 class="card-title">Deluxe Room</h5>
          <p class="card-text">Experience the ultimate luxury and comfort.</p>
          <a href="ddetails.php" class="btn btn-primary">View Details</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card room-card">
        <img src="./image/l1.jpg" class="card-img-top" alt="Luxury Room">
        <div class="card-body text-center">
          <h5 class="card-title">Luxury Room</h5>
          <p class="card-text">Indulge in unmatched elegance with our Luxury Rooms.</p>
          <a href="ldetails.php" class="btn btn-primary">View Details</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!--  Amenities -->
<section class="container amenities">
  <h2>Included with Every Stay</h2>
  <div class="amenities-list">
    <div class="amenity"><i class="fas fa-tv"></i> Television</div>
    <div class="amenity"><i class="fas fa-wifi"></i> Wi-Fi Included</div>
    <div class="amenity"><i class="fas fa-wind"></i> Air Conditioning</div>
    <div class="amenity"><i class="fas fa-bath"></i> Bath Amenities</div>
    <div class="amenity"><i class="fas fa-concierge-bell"></i> Room Service</div>
    <div class="amenity"><i class="fas fa-broom"></i> Daily Housekeeping</div>
    <div class="amenity"><i class="fas fa-briefcase"></i> Work Space</div>
  </div>
</section>

<!--  Footer -->
<footer style="background: #003366; color: white; padding: 20px; text-align: center;">
  <p>&copy; 2025 Sunrise Hotel. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>
