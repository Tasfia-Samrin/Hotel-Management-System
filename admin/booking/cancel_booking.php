<?php
require_once '../../DatabaseConnection.php';
require_once '../../observer/BookingSubject.php';
require_once '../../observer/GuestEmailNotifier.php';

use Observer\BookingSubject;
use Observer\GuestEmailNotifier;

$bookingId = $_GET['id'] ?? null;
if (!$bookingId || !is_numeric($bookingId)) {
    die("Invalid booking ID.");
}

$conn = DatabaseConnection::getInstance()->getConnection();

$stmt = $conn->prepare("
    SELECT b.id, b.room_id, r.room_number, u.name, u.email
    FROM booking b
    JOIN rooms r ON b.room_id = r.id
    JOIN users u ON b.guest_id = u.id
    WHERE b.id = ?
");
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) die("Booking not found.");

$update = $conn->prepare("UPDATE booking SET status = 'Cancelled' WHERE id = ?");
$update->bind_param("i", $bookingId);
if (!$update->execute()) die("Failed to cancel booking.");
$update->close();

$notifier = new GuestEmailNotifier();
$subject = new BookingSubject();
$subject->attach($notifier);
$subject->notify('cancelled', [
    'booking_id' => $booking['id'],
    'room'       => $booking['room_number'],
    'name'       => $booking['name'],
    'email'      => $booking['email']
]);

$emailStatus = $notifier->renderedMessage;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Cancelled</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar { background-color: #003366 !important; }
    .btn-back {
      background-color: #ffffff;
      color: #003366;
      border: 1px solid #003366;
    }
    .btn-back:hover { background-color: #f0f0f0; }
  </style>
</head>
<body>

<nav class="navbar navbar-dark">
  <div class="container">
    <span class="navbar-brand fw-bold">Sunrise Hotel</span>
    <a href="a-booking.php" class="btn btn-sm btn-back">⬅ Back to Booking Management</a>
  </div>
</nav>

<div class="container mt-5">
  <div class="alert alert-warning text-center">
    ❌ Booking has been <strong>cancelled</strong> and guest notified.
  </div>
  <?= $emailStatus ?>
</div>

</body>
</html>
