<?php
require_once '../classes/User.php';
require_once '../classes/Guest.php';
require_once '../DatabaseConnection.php';

session_start();

//  Check login & role
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'guest') {
    echo "<script>alert('Access Denied'); window.location.href='../login.php';</script>";
    exit;
}

$user = $_SESSION['user'];
$guestId = $user->getId();

$db = DatabaseConnection::getInstance();
$conn = $db->getConnection();

// Fetch latest room service requests
$stmt = $conn->prepare("
    SELECT room_number, item_category, item_name, description, status, progress_status, requested_at
    FROM room_service_requests
    WHERE guest_id = ?
    ORDER BY requested_at DESC
");
$stmt->bind_param("i", $guestId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Room Service Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 70px;
    }
    .navbar {
      background-color: #003366 !important;
    }
    .badge {
      font-size: 0.8rem;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
    <div class="ms-auto">
      <a href="g_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2 class="text-center mb-4 text-primary">My Room Service Requests</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>Room</th>
            <th>Request</th>
            <th>Description</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Message</th>
            <th>Requested At</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): 
          $status = $row['status'];
          $progress = $row['progress_status'] ?? 'Not Started';

          $statusBadge = match ($status) {
              'Approved' => 'bg-success',
              'Cancelled' => 'bg-danger',
              default => 'bg-warning text-dark'
          };

          $progressBadge = match ($progress) {
              'In Progress' => 'bg-warning text-dark',
              'Completed' => 'bg-success',
              default => 'bg-secondary'
          };

          $message = match ($status) {
              'Approved' => '✅ Approved! We are on it.',
              'Cancelled' => '❌ Sorry, we couldn’t fulfill your request.',
              default => '⏳ Waiting for confirmation...'
          };
        ?>
          <tr>
            <td><?= htmlspecialchars($row['room_number']) ?></td>
            <td><strong><?= htmlspecialchars($row['item_category']) ?>:</strong> <?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><span class="badge <?= $statusBadge ?>"><?= $status ?></span></td>
            <td>
              <?php if ($status === 'Approved'): ?>
                <span class="badge <?= $progressBadge ?>"><?= $progress ?></span>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
            <td><?= $message ?></td>
            <td><?= date("M d, Y H:i", strtotime($row['requested_at'])) ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-center text-muted">No room service requests found.</p>
  <?php endif; ?>
</div>

</body>
</html>
