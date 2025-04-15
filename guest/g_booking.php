<?php
session_start();
require_once '../DatabaseConnection.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$guestId = $_SESSION['user_id'];
$conn = DatabaseConnection::getInstance()->getConnection();

$stmt = $conn->prepare("
    SELECT b.id, r.room_number, r.price, b.checkin_date, b.checkout_date, b.status, b.payment_method, b.created_at
    FROM booking b
    JOIN rooms r ON b.room_id = r.id
    WHERE b.guest_id = ?
    ORDER BY b.created_at DESC
");
$stmt->bind_param("i", $guestId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings - Sunrise Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 100px; 
        }

        .table th, .table td {
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navbar  -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#" style="font-size: 25px;">Sunrise Hotel</a>
        <div class="ms-auto">
            <a href="g_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
        </div>
    </div>
</nav>

<!-- Bookings Title -->
<div class="container">
    <h2 class="text-center mb-4">🗂️ My Bookings</h2>

    <!-- Bookings Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Booked On</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= 'BK' . str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td><?= htmlspecialchars($row['room_number']) ?></td>
                        <td><?= $row['checkin_date'] ?></td>
                        <td><?= $row['checkout_date'] ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', $row['payment_method'])) ?></td>
                        <td><span class="badge bg-success"><?= $row['status'] ?></span></td>
                        <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
