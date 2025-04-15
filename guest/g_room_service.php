<?php
require_once '../classes/User.php';
require_once '../classes/Guest.php';
require_once '../DatabaseConnection.php';

session_start();

//  Restrict access to guests only
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'guest') {
    echo "<script>alert('Access Denied'); window.location.href='../login.php';</script>";
    exit;
}

//  Safely unserialize guest
$user = $_SESSION['user'];
$guestId = $user->getId();
$guestName = $user->getName();
$roomNumber = '';
$successMessage = '';

$db = DatabaseConnection::getInstance();
$conn = $db->getConnection();

//  Get latest confirmed room booking
$roomSql = $conn->prepare("
    SELECT r.room_number 
    FROM booking b 
    JOIN rooms r ON b.room_id = r.id 
    WHERE b.guest_id = ? AND b.status = 'Confirmed' 
    ORDER BY b.id DESC LIMIT 1
");
$roomSql->bind_param("i", $guestId);
$roomSql->execute();
$roomResult = $roomSql->get_result();
if ($roomResult && $roomResult->num_rows > 0) {
    $roomNumber = $roomResult->fetch_assoc()['room_number'];
}

//  Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';
    $items = $_POST['items'] ?? [];
    $otherItem = trim($_POST['other_item'] ?? '');
    $description = $_POST['description'] ?? '';

    if (!empty($otherItem)) {
        $items[] = $otherItem;
    }

    $itemsText = implode(', ', $items);

    $stmt = $conn->prepare("
        INSERT INTO room_service_requests 
        (guest_id, room_number, item_category, item_name, description) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issss", $guestId, $roomNumber, $category, $itemsText, $description);

    if ($stmt->execute()) {
        $successMessage = "✅ Your room service request has been submitted. Please wait for confirmation.";
    } else {
        $successMessage = "❌ Something went wrong. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request Room Service</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 70px;
      background-color: #f9f9f9;
    }
    .navbar {
      background-color: #003366 !important;
    }
    .container {
      max-width: 720px;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-check-label {
      cursor: pointer;
    }
  </style>
</head>
<body>

<!--  Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
    <div class="ms-auto">
      <a href="g_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
    </div>
  </div>
</nav>


<div class="container mt-4">
  <h2 class="text-center mb-4 text-primary">Room Service Request</h2>

  <?php if (!empty($successMessage)): ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($successMessage) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Room Number</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($roomNumber) ?>" disabled>
    </div>

    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="category" id="category" class="form-select" required onchange="showOptions()">
        <option value="" selected disabled>Select Category</option>
        <option value="Breakfast">Breakfast</option>
        <option value="Lunch">Lunch</option>
        <option value="Dinner">Dinner</option>
        <option value="Snacks">Snacks</option>
        <option value="Drinks">Drinks</option>
        <option value="Toiletries">Toiletries</option>
        <option value="Cleaning">Cleaning</option>
        <option value="Maintenance">Maintenance</option>
      </select>
    </div>

    <div id="items-container" class="mb-3"></div>

    <div class="mb-3">
      <label class="form-label">Additional Description</label>
      <textarea name="description" class="form-control" rows="3" placeholder="Optional details..."></textarea>
    </div>

    <button type="submit" class="btn btn-primary w-100">Submit Request</button>
  </form>
</div>

<!--  Dynamic Options -->
<script>
const options = {
  Breakfast: ['Bread', 'Eggs', 'Juice', 'Paratha', 'Tea', 'Coffee'],
  Lunch: ['Rice', 'Chicken Curry', 'Dal', 'Salad', 'Fish Fry'],
  Dinner: ['Biryani', 'Naan', 'Veg Soup', 'Paneer Curry', 'Grilled Chicken'],
  Snacks: ['Chips', 'Sandwich', 'Cake', 'Fries', 'Samosa'],
  Drinks: ['Tea', 'Coffee', 'Soft Drinks', 'Mineral Water'],
  Toiletries: ['Toothpaste', 'Toothbrush', 'Shampoo', 'Soap', 'Tissue', 'Towel'],
  Cleaning: ['Room Cleaning', 'Bathroom Cleaning', 'Change Bedsheet', 'Replace Towels'],
  Maintenance: ['AC Not Working', 'TV Issue', 'Water Leakage', 'Light Not Working']
};

function showOptions() {
  const category = document.getElementById('category').value;
  const container = document.getElementById('items-container');
  container.innerHTML = '';

  if (options[category]) {
    options[category].forEach(item => {
      const checkbox = document.createElement('div');
      checkbox.classList.add('form-check');
      checkbox.innerHTML = `
        <input class="form-check-input" type="checkbox" name="items[]" value="${item}" id="${item}">
        <label class="form-check-label" for="${item}">${item}</label>
      `;
      container.appendChild(checkbox);
    });

    const otherDiv = document.createElement('div');
    otherDiv.classList.add('form-check', 'mt-2');
    otherDiv.innerHTML = `
      <input class="form-check-input" type="checkbox" id="otherCheck" onchange="toggleOtherInput()">
      <label class="form-check-label" for="otherCheck">Other</label>
      <div id="otherInput" style="display:none; margin-top: 5px;">
        <input type="text" name="other_item" class="form-control form-control-sm" placeholder="Please specify...">
      </div>
    `;
    container.appendChild(otherDiv);
  }
}

function toggleOtherInput() {
  const otherInput = document.getElementById('otherInput');
  const isChecked = document.getElementById('otherCheck').checked;
  otherInput.style.display = isChecked ? 'block' : 'none';
}
</script>

</body>
</html>
