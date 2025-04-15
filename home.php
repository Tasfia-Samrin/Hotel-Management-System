<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Hero Section */
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

    /* Sticky Navbar */
    .navbar {
        background-color: #003366 !important;
        position: sticky;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Room Preview */
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
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Sunrise Hotel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
                <li class="nav-item"><a class="nav-link" href="contactus.php">Contact Us</a></li>
                <li class="nav-item"><a href="login.php" class="btn btn-outline-light mx-2">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1>Experience Luxury & Comfort</h1>
        <p>Book your stay with ease and enjoy premium services.</p>
        <a href="#rooms" class="btn btn-lg btn-warning">Explore Rooms</a>
    </div>
</div>

<!-- Room Preview Section -->
<section id="rooms" class="container my-5">
    <h2 class="text-center mb-4">Our Rooms</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card room-card">
                <img src="./image/s1.jpg" class="card-img-top" alt="Standard Room">
                <div class="card-body text-center">
                    <h5 class="card-title">Standard Room</h5>
                    <p class="card-text">Enjoy a comfortable stay with all modern amenities.</p>
                    <a href="sdetails.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card room-card">
                <img src="./image/d1.jpg" class="card-img-top" alt="Deluxe Room">
                <div class="card-body text-center">
                    <h5 class="card-title">Deluxe Rooms</h5>
                    <p class="card-text">Experience the ultimate luxury and comfort.</p>
                    <a href="ddetails.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card room-card">
                <img src="./image/l1.jpg" class="card-img-top" alt="Luxury Suite">
                <div class="card-body text-center">
                    <h5 class="card-title">Luxury Suite</h5>
                    <p class="card-text">Indulge in unmatched elegance and service.</p>
                    <a href="ldetails.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer  -->
<footer class="mt-5 py-3 text-center text-white" style="background-color: #003366;">
    <p class="mb-0">&copy; 2025 Sunrise Hotel. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
