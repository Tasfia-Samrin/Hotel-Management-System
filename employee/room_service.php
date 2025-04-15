<?php 
require_once '../classes/User.php';
require_once '../classes/Employee.php';
require_once '../DatabaseConnection.php';

session_start();

//  Check if employee is logged in
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'employee') {
    echo "<script>alert('Access Denied!'); window.location.href='../login.php';</script>";
    exit;
}

$user = $_SESSION['user'];
$employeeId = $user->getId();
$employeeName = $user->getName();

$db = DatabaseConnection::getInstance();
$conn = $db->getConnection();

// Check if employee has a Room Service task
$taskStmt = $conn->prepare("SELECT id FROM task WHERE emp_id = ? AND task = 'Room Service'");
$taskStmt->bind_param("i", $employeeId);
$taskStmt->execute();
$taskResult = $taskStmt->get_result();
if ($taskResult->num_rows === 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Access Denied</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <style>
        body {
          background-color: #f8f9fa;
          padding-top: 80px;
        }
        .navbar {
          background-color: #003366 !important;
        }
        .message-box {
          max-width: 500px;
          margin: 80px auto;
          background: white;
          padding: 30px;
          border-radius: 8px;
          text-align: center;
          box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
      </style>
    </head>
    <body>
    
    <nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
      </div>
    </nav>
    
    <div class="message-box">
      <h3 class="text-danger mb-3">🚫 Access Restricted</h3>
      <p>You are <strong>not assigned to Room Service</strong> at the moment.</p>
      <a href="e_dashboard.php" class="btn btn-primary mt-3">⬅ Back to Dashboard</a>
    </div>
    
    </body>
    </html>
    <?php
    exit;
    }    

//  Handle Approve / Cancel actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];
    $status = $action === 'approve' ? 'Approved' : 'Cancelled';

    $update = $conn->prepare("UPDATE room_service_requests SET status = ?, handled_by = ?, handled_at = NOW() WHERE id = ?");
    $update->bind_param("sii", $status, $employeeId, $requestId);
    $update->execute();
}

//  Fetch all requests
$query = "
SELECT 
  r.id, u.name AS guest_name, r.room_number, r.item_category, r.item_name, 
  r.description, r.status, r.requested_at, r.handled_by, r.handled_at,
  e.name AS handler_name
FROM room_service_requests r
JOIN users u ON r.guest_id = u.id
LEFT JOIN users e ON r.handled_by = e.id
ORDER BY r.requested_at DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Room Service Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar {
      background-color: #003366 !important;
    }
    .badge {
      font-size: 0.8rem;
    }
    .table td, .table th {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<!--  Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark px-3">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
    <div class="ms-auto">
      <a href="e_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
    </div>
  </div>
</nav>

<!--  Content -->
<div class="container my-4">
  <h2 class="text-center mb-4 text-primary">Room Service Requests</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>Room No</th>
            <th>Guest Name</th>
            <th>Request</th>
            <th>Description</th>
            <th>Requested At</th>
            <th>Status</th>
            <th>Action</th>
            <th>Handled By</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['room_number']) ?></td>
              <td><?= htmlspecialchars($row['guest_name']) ?></td>
              <td><strong><?= htmlspecialchars($row['item_category']) ?>:</strong> <?= htmlspecialchars($row['item_name']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td><?= date("M d, Y H:i", strtotime($row['requested_at'])) ?></td>
              <td>
                <?php
                  $status = $row['status'];
                  $badge = match ($status) {
                      'Pending' => 'bg-warning text-dark',
                      'Approved' => 'bg-success',
                      'Cancelled' => 'bg-danger',
                      default => 'bg-secondary'
                  };
                ?>
                <span class="badge <?= $badge ?>"><?= $status ?></span>
              </td>
              <td>
                <?php if ($status === 'Pending'): ?>
                  <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="action" value="approve" class="btn btn-sm btn-success mb-1">Approve</button>
                    <button type="submit" name="action" value="cancel" class="btn btn-sm btn-danger">Cancel</button>
                  </form>
                <?php else: ?>
                  <small class="text-muted">No actions</small>
                <?php endif; ?>
              </td>
              <td>
                <?= $row['handler_name'] ? htmlspecialchars($row['handler_name']) . "<br><small>(" . date("M d, H:i", strtotime($row['handled_at'])) . ")</small>" : '-' ?>
              </td>
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
