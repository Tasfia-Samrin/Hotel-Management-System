<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Room - Sunrise Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .booking-container {
            max-width: 600px;
            margin: 80px auto 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-book {
            background: #D4AF37;
            color: white;
            font-weight: bold;
            width: 100%;
        }
        .navbar {
            background-color: #003366 !important;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>

<!--   Navbar -->
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

<!--  Booking Form -->
<div class="container booking-container">
    <h2 class="text-center text-primary">Book Your Stay</h2>
    <form action="process_booking.php" method="POST">
        <div class="mb-3">
            <label for="checkin" class="form-label">Check-in Date</label>
            <input type="date" class="form-control" name="checkin" id="checkin" required>
        </div>

        <div class="mb-3">
            <label for="checkout" class="form-label">Check-out Date</label>
            <input type="date" class="form-control" name="checkout" id="checkout" required>
        </div>

        <div class="mb-3">
            <label for="room" class="form-label">Select Room</label>
            <select class="form-select" name="room" id="room" required>
                <option value="">Please select check-in and check-out dates first</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="guests" class="form-label">Number of Guests</label>
            <input type="number" class="form-control" name="guests" min="1" max="4" required>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select class="form-select" name="payment_method" required>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cash_on_arrival">Cash on Arrival</option>
            </select>
        </div>

        <button type="submit" class="btn btn-book">Confirm Booking & Proceed to Payment</button>
    </form>
</div>

<script>
    async function loadAvailableRooms() {
        const checkin = document.getElementById("checkin").value;
        const checkout = document.getElementById("checkout").value;
        const roomSelect = document.getElementById("room");

        if (checkin && checkout) {
            const response = await fetch(`load_rooms.php?checkin=${checkin}&checkout=${checkout}`);
            const rooms = await response.json();

            roomSelect.innerHTML = rooms.length
                ? rooms.map(r => `<option value="${r.room_number}">Room ${r.room_number} - $${r.price}/Night</option>`).join("")
                : `<option value="">No available rooms for selected dates</option>`;
        }
    }

    document.getElementById("checkin").addEventListener("change", loadAvailableRooms);
    document.getElementById("checkout").addEventListener("change", loadAvailableRooms);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
