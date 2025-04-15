<?php
require_once '../DatabaseConnection.php';
$conn = DatabaseConnection::getInstance()->getConnection();

// luxury type = ID 3
$typeId = 3;
$result = $conn->query("SELECT * FROM rooms WHERE room_type_id = $typeId");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Luxury Rooms - Sunrise Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar {
            background-color: #003366 !important;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .room-card {
            border: 2px solid #D4AF37;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .room-card:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        .room-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .amenities-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding-left: 0;
        }
        .amenities-list li {
            list-style: none;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .amenities-list i {
            color: #D4AF37;
            margin-right: 10px;
        }
        .book-now {
            background: #D4AF37;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            display: inline-block;
            padding: 10px;
            text-decoration: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<!--  Navbar  -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="g_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="g_booking.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="g_payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
                <li class="nav-item"><a class="nav-link" href="g_dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a></li>
                <li class="nav-item">
                    <a class="nav-link logout" href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!--  Room list -->
<div class="container mt-5">
    <h2 class="text-center text-primary">Luxury Rooms</h2>
    <p class="text-center text-muted">Indulge in Unmatched Elegance with Our Luxury Rooms.</p>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="col-md-4 mb-4">
                <div class="room-card p-3">
                   
                    <h4 class="text-warning mt-3">$<?= $row['price']; ?> / Night</h4>
                    <ul class="amenities-list">
                        <?php
                        $amenities = explode(",", $row['amenities']);
                        foreach ($amenities as $a) {
                            echo "<li><i class='fas fa-check'></i> " . trim($a) . "</li>";
                        }
                        ?>
                    </ul>
                    <a href="g_booknow.php?room=<?= urlencode($row['room_number']) ?>" class="book-now">Book Now</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Footer -->
<footer style="background: #003366; color: white; padding: 10px; text-align: center; margin-top: 40px;">
    <p>&copy; 2025 Sunrise Hotel. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
