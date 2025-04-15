<?php
session_start();
require_once '../DatabaseConnection.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$guestId = $_SESSION['user_id'];
$conn = DatabaseConnection::getInstance()->getConnection();

$stmt = $conn->prepare("
    SELECT b.id, r.room_number, r.price, b.checkin_date, b.checkout_date, b.payment_method, b.created_at
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
    <title>Payment History - Sunrise Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 30px; }
        .table th, .table td { text-align: center; }
        .navbar {
            background-color: #003366 !important;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>

<!--  Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
        <div class="ms-auto">
            <a href="g_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4 mt-4">Payment History</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Room</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Booked On</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                while ($row = $result->fetch_assoc()):
                    $days = (strtotime($row['checkout_date']) - strtotime($row['checkin_date'])) / (60 * 60 * 24);
                    $amount = $days * $row['price'];
                ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= 'BK' . str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td><?= $row['room_number'] ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', $row['payment_method'])) ?></td>
                        <td>$<?= number_format($amount, 2) ?></td>
                        <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
