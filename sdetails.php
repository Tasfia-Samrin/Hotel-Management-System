<?php
require_once 'DatabaseConnection.php';
$conn = DatabaseConnection::getInstance()->getConnection();

// Standard type = ID 1
$typeId = 1;
$result = $conn->query("SELECT * FROM rooms WHERE room_type_id = $typeId");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standard Rooms - Sunrise Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar {
            background-color: #003366 !important;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .room-card {
            border: 2px solid #D4AF37;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .room-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .room-image {
            width: 100%;
            height: 250px;
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
            font-size: 18px;
        }

        .book-now {
            background: #D4AF37;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
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

<!--  Main Content -->
<div class="container mt-5">
    <h2 class="text-center text-primary">Standard Rooms</h2>
    <p class="text-center text-muted">Enjoy a relaxing stay in our cozy and affordable Standard Rooms.</p>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="col-md-4 mb-4">
                <div class="room-card p-3">
                    <img src="./image/d1.jpg" class="room-image" alt="Room Image">
                    <h4 class="text-warning mt-3">$<?= $row['price']; ?> / Night</h4>
                    <ul class="amenities-list">
                        <?php
                        $amenities = explode(",", $row['amenities']);
                        foreach ($amenities as $a) {
                            echo "<li><i class='fas fa-check'></i> " . trim($a) . "</li>";
                        }
                        ?>
                    </ul>
                    <button class="btn book-now" onclick="checkLogin()">Book Now</button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!--  Footer -->
<footer style="background: #003366; color: white; padding: 20px; text-align: center; margin-top: 40px;">
    <p>&copy; 2025 Sunrise Hotel. All Rights Reserved.</p>
</footer>


<script>
    function checkLogin() {
        let isLoggedIn = localStorage.getItem("loggedIn");
        if (isLoggedIn === "true") {
            window.location.href = "booking.php";
        } else {
            window.location.href = "login.php";
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
