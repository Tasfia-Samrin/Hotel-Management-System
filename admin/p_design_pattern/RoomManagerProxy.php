<?php
require_once 'RoomManagerInterface.php';
require_once 'RealRoomManagerInterface.php';

class RoomManagerProxy implements RoomManagerInterface {
    private $realManager;

    public function __construct() {
        $this->realManager = new RealRoomManager();
    }

    public function addRoom($roomNumber, $roomType, $price, $amenities) {
        $this->realManager->addRoom($roomNumber, $roomType, $price, $amenities);
    }

    public function deleteRoom($roomId) {
        $conn = DatabaseConnection::getInstance()->getConnection();
        $stmt = $conn->prepare("SELECT status FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $roomId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['status'] === 'available') {
            $this->realManager->deleteRoom($roomId);
        } else {
            echo "<script>alert('Cannot delete: Room is currently booked.');</script>";
        }
    }

    public function editRoom($roomId, $roomNumber, $roomTypeId, $price, $amenities) {
        $this->realManager->editRoom($roomId, $roomNumber, $roomTypeId, $price, $amenities);
    }

    public function updateRoomStatus($roomId, $status) {
        $this->realManager->updateRoomStatus($roomId, $status);
    }

    public function getRoomDetails($roomId) {
        return $this->realManager->getRoomDetails($roomId);
    }
}
?>


