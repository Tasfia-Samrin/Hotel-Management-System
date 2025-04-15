<?php
session_start();
require_once '../DatabaseConnection.php';

// Payment Strategy Classes
require_once 'Payment/PaymentStrategyInterface.php';
require_once 'Payment/CreditCardPayment.php';
require_once 'Payment/PayPalPayment.php';
require_once 'Payment/BankTransferPayment.php';
require_once 'Payment/CashOnArrivalPayment.php';
require_once 'Payment/PaymentContext.php';

use Guest\Payment\CreditCardPayment;
use Guest\Payment\PayPalPayment;
use Guest\Payment\BankTransferPayment;
use Guest\Payment\CashOnArrivalPayment;
use Guest\Payment\PaymentContext;

//  Authorization
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

//  Collect form inputs
$guestId = $_SESSION['user_id'];
$roomNumber = $_POST['room'] ?? '';
$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$guests = $_POST['guests'] ?? 1;
$paymentMethod = $_POST['payment_method'] ?? 'credit_card';

$conn = DatabaseConnection::getInstance()->getConnection();

//  Get room ID and price
$stmtRoom = $conn->prepare("SELECT id, price FROM rooms WHERE room_number = ? LIMIT 1");
$stmtRoom->bind_param("s", $roomNumber);
$stmtRoom->execute();
$stmtRoom->bind_result($room_id, $price);
$stmtRoom->fetch();
$stmtRoom->close();

//  Invalid room
if (!$room_id) {
    die("Invalid room selected.");
}

//  Days & total amount
$days = (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24);
if ($days < 1) {
    die("Check-out must be after check-in.");
}
$amount = $days * $price;

//  Payment Strategy
$context = new PaymentContext();
switch ($paymentMethod) {
    case 'paypal': $context->setStrategy(new PayPalPayment()); break;
    case 'bank_transfer': $context->setStrategy(new BankTransferPayment()); break;
    case 'cash_on_arrival': $context->setStrategy(new CashOnArrivalPayment()); break;
    default: $context->setStrategy(new CreditCardPayment()); break;
}
$paymentResult = $context->executePayment($amount);

//  Insert booking into DB as 'Pending'
$stmt = $conn->prepare("
    INSERT INTO booking (guest_id, room_id, checkin_date, checkout_date, guests, status, payment_method)
    VALUES (?, ?, ?, ?, ?, 'Pending', ?)
");

if (!$stmt) {
    die("❌ Prepare failed: " . $conn->error);
}

$stmt->bind_param("iissis", $guestId, $room_id, $checkin, $checkout, $guests, $paymentMethod);
$executed = $stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar {
            background-color: #003366 !important;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>

<!--  Blue Navbar -->
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

<!--  Confirmation Message -->
<div class="container mt-5">
    <?php if ($executed): ?>
        <div class="alert alert-success text-center">
            <h3>✅ Booking Request Submitted!</h3>
            <p>Your booking is currently <strong>Pending</strong> approval.</p>
            <p><strong>Room:</strong> <?= htmlspecialchars($roomNumber) ?></p>
            <p><strong>Guests:</strong> <?= $guests ?></p>
            <p><strong>Check-in:</strong> <?= $checkin ?></p>
            <p><strong>Check-out:</strong> <?= $checkout ?></p>
            <p><strong>Total:</strong> $<?= number_format($amount, 2) ?></p>
            <p><strong>Payment:</strong> <?= $paymentResult ?></p>
            <a href="g_booking.php" class="btn btn-primary mt-3">📖 View My Bookings</a>
            <a href="g_dashboard.php" class="btn btn-outline-secondary mt-3 ms-2">⬅ Back to Dashboard</a>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">❌ Booking failed: <?= $stmt->error ?></div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
