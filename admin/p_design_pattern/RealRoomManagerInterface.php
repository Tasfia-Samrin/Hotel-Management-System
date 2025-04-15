<?php
require_once 'RoomManagerInterface.php';
require_once __DIR__ . '/../../DatabaseCOnnection.php';

class RealRoomManager implements RoomManagerInterface {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::getInstance()->getConnection();
    }

    public function addRoom($roomNumber, $roomType, $price, $amenities) {
        
        $checkStmt = $this->conn->prepare("SELECT id FROM rooms WHERE room_number = ?");
        $checkStmt->bind_param("s", $roomNumber);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<div class='alert alert-danger'>A room with number <strong>$roomNumber</strong> already exists!</div>";
            $checkStmt->close();
            return;
        }
        $checkStmt->close();

        // Proceed with inserting the new room
        $stmt = $this->conn->prepare("INSERT INTO rooms (room_number, room_type_id, price, amenities, status) VALUES (?, ?, ?, ?, 'available')");
        $stmt->bind_param("ssis", $roomNumber, $roomType, $price, $amenities);
        $stmt->execute();
        $stmt->close();

        echo "<div class='alert alert-success'>Room <strong>$roomNumber</strong> added successfully!</div>";
    }

    public function deleteRoom($roomId) {
        $stmt = $this->conn->prepare("DELETE FROM rooms WHERE id = ? AND status = 'available'");
        $stmt->bind_param("i", $roomId); 
        $stmt->execute();
    }

    public function editRoom($roomId, $roomNumber, $roomTypeId, $price, $amenities) {
        $stmt = $this->conn->prepare("UPDATE rooms SET room_number=?, room_type_id=?, price=?, amenities=? WHERE id=?");
        $stmt->bind_param("ssisi", $roomNumber, $roomTypeId, $price, $amenities, $roomId);
        $stmt->execute();
    }

    public function updateRoomStatus($roomId, $status) {
        $stmt = $this->conn->prepare("UPDATE rooms SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $roomId);
        $stmt->execute();
    }

    public function getRoomDetails($roomId) {
        $stmt = $this->conn->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $roomId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
}
?>



