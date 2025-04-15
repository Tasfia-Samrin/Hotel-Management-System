<?php
session_start();
require_once 'p_design_pattern/RoomManagerProxy.php';
require_once '../DatabaseConnection.php';
$conn = DatabaseConnection::getInstance()->getConnection();
$roomManager = new RoomManagerProxy($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addRoom'])) {
        $roomManager->addRoom($_POST['roomNumber'], $_POST['roomType'], $_POST['roomPrice'], $_POST['amenities']);
    }
  
    if (isset($_POST['deleteRoom'])) {
        $roomManager->deleteRoom($_POST['roomId']);
    }
  
    if (isset($_POST['updateStatus'])) {
        $roomManager->updateRoomStatus($_POST['roomId'], $_POST['newStatus']);
    }

    if (isset($_POST['editRoom'])) {
        $roomManager->editRoom($_POST['roomId'], $_POST['roomNumber'], $_POST['room_type_id'], $_POST['price'], $_POST['amenities']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Rooms - HMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 70px; 
    }

    .navbar {
      background-color: #0d1b2a; 
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: bold;
      color: white !important;
      font-size: 1.5rem;
    }

    .container {
      padding: 40px 20px;
    }

    .card {
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .room-card {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .room-image {
      width: 100%;
      height: 200px;
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
    }
  </style>
</head>
<body>
<!--  Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Sunrise Hotel</a>
    <div class="ms-auto">
      <a href="admin_dashboard.php" class="btn btn-light btn-sm">← Back to Dashboard</a>
    </div>
  </div>
</nav>

<div class="container">
  <h2 class="mb-4 text-center">Manage Rooms</h2>

  <!-- Add Room Form -->
  <div class="card p-4 mb-5">
    <h4 class="mb-3">Add New Room</h4>
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Room Number</label>
          <input type="text" class="form-control" name="roomNumber" placeholder="101" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Room Type</label>
          <select class="form-select" name="roomType" required>
            <option selected disabled>Select Type</option>
            <option value="1">Standard</option>
            <option value="2">Deluxe</option>
            <option value="3">Luxury</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Price (per night)</label>
          <input type="number" class="form-control" name="roomPrice" placeholder="1500" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Amenities (comma separated)</label>
          <input type="text" class="form-control" name="amenities" placeholder="Wi-Fi, AC, TV" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary" name="addRoom">Add Room</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Room List Section -->
  <div class="card p-4">
    <h4 class="mb-3">Room List</h4>
    <div class="row g-4">
      <?php
      $result = $conn->query("SELECT r.*, rt.type_name as room_type 
                             FROM rooms r 
                             JOIN room_types rt ON r.room_type_id = rt.id");

      while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4">';
        echo '  <div class="room-card p-3">';
        echo '    <h4 class="text-warning mt-3">$' . $row['price'] . ' / Night</h4>';
        echo '    <p class="fw-semibold">' . $row['room_type'] . ' - Room ' . $row['room_number'] . '</p>';
        echo '    <ul class="amenities-list">';
        foreach (explode(',', $row['amenities']) as $amenity) {
          echo '      <li><i class="fas fa-check"></i> ' . trim($amenity) . '</li>';
        }
        echo '    </ul>';
        echo '    <div class="mt-3">';
        echo '      <strong>Status:</strong> ' . ucfirst($row['status']) . '<br>';
        echo '      <form method="POST" class="mt-2">';
        echo '        <input type="hidden" name="roomId" value="' . $row['id'] . '">';
        echo '        <select name="newStatus" class="form-select form-select-sm w-100" onchange="this.form.submit()">';
        echo '          <option value="available" ' . ($row['status'] == 'available' ? 'selected' : '') . '>available</option>';
        echo '          <option value="booked" ' . ($row['status'] == 'booked' ? 'selected' : '') . '>booked</option>';
        echo '          <option value="maintenance" ' . ($row['status'] == 'maintenance' ? 'selected' : '') . '>maintenance</option>';
        echo '        </select>';
        echo '        <input type="hidden" name="updateStatus" value="1">';
        echo '      </form>';
        echo '    </div>';
        echo '    <div class="d-flex mt-6 ">';
        echo '    <form method="POST" class="d-inline mt-3 me-2">';
        echo '      <input type="hidden" name="roomId" value="' . $row['id'] . '">';
        echo '      <button type="submit" class="btn btn-danger btn-sm" name="deleteRoom">Delete</button>';
        echo '    </form>';
        echo '<form method="GET" action="editroom.php" class="d-inline mt-3 me-2">';
        echo '  <input type="hidden" name="roomId" value="' . $row['id'] . '">';
        echo '  <button type="submit" class="btn btn-secondary btn-sm">Edit</button>';
        echo '</form>';
        echo '  </div>';
        echo '  </div>';
        echo '</div>';
      }
      ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>