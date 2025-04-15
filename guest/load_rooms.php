<?php
require_once '../DatabaseConnection.php';
$conn = DatabaseConnection::getInstance()->getConnection();

$checkin = $_GET['checkin'] ?? null;
$checkout = $_GET['checkout'] ?? null;

if (!$checkin || !$checkout) {
    echo json_encode([]);
    exit;
}

// Get only rooms not booked within the selected range
$stmt = $conn->prepare("
    SELECT r.id, r.room_number, r.price
    FROM rooms r
    WHERE r.status = 'available'
    AND NOT EXISTS (
        SELECT 1 FROM booking b
        WHERE b.room_id = r.id
        AND (
            (b.checkin_date <= ? AND b.checkout_date > ?)
            OR (b.checkin_date < ? AND b.checkout_date >= ?)
            OR (b.checkin_date >= ? AND b.checkout_date <= ?)
        )
    )
");
$stmt->bind_param("ssssss", $checkin, $checkin, $checkout, $checkout, $checkin, $checkout);
$stmt->execute();
$result = $stmt->get_result();

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

echo json_encode($rooms);
