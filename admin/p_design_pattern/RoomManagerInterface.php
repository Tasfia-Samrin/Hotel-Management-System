<?php
interface RoomManagerInterface {
    public function addRoom($roomNumber, $roomType, $price, $amenities);
    public function deleteRoom($roomId);
    public function editRoom($roomId, $roomNumber, $roomTypeId, $price, $amenities);
    public function updateRoomStatus($roomId, $status);
    public function getRoomDetails($roomId);
}
?>