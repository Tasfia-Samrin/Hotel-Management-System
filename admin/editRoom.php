<?php
require_once 'p_design_pattern/RoomManagerProxy.php';
$roomManager = new RoomManagerProxy();

// Handle room ID from GET
if (!isset($_GET['roomId'])) {
    echo "Invalid request. Room ID is missing.";
    exit;
}

$roomId = $_GET['roomId'];

// Fetch room details through proxy
$room = $roomManager->getRoomDetails($roomId);

if (!$room) {
    echo "Room not found.";
    exit;
}

// Handle form submission
if (isset($_POST['saveEdit'])) {
    $updatedRoomNumber = $_POST['roomNumber'];
    $updatedRoomType = $_POST['roomType'];
    $updatedPrice = $_POST['price'];
    $updatedAmenities = $_POST['amenities'];

    $roomManager->editRoom($roomId, $updatedRoomNumber, $updatedRoomType, $updatedPrice, $updatedAmenities);

    // Redirect back to room management page
    header("Location: manageroom.php?edit=success");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px; 
            background-color: #f8f9fa;
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
            max-width: 800px;
            margin-top: 30px;
        }
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
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
    <div class="form-container">
        <h2 class="mb-4 text-center">Edit Room Details</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Room Number</label>
                <input type="text" class="form-control" name="roomNumber" value="<?php echo $room['room_number']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Room Type</label>
                <select class="form-select" name="roomType" required>
                    <option value="1" <?php if ($room['room_type_id'] == 1) echo 'selected'; ?>>Standard</option>
                    <option value="2" <?php if ($room['room_type_id'] == 2) echo 'selected'; ?>>Deluxe</option>
                    <option value="3" <?php if ($room['room_type_id'] == 3) echo 'selected'; ?>>Luxury</option>
                    <!-- can add more room types here -->
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" class="form-control" name="price" value="<?php echo $room['price']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Amenities</label>
                <input type="text" class="form-control" name="amenities" value="<?php echo $room['amenities']; ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" name="saveEdit" class="btn btn-success">Save Changes</button>
                <a href="manageroom.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>