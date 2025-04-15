<?php
session_start();
require_once '../DatabaseConnection.php';

// Check if employee is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    echo "<script>alert('Access Denied!'); window.location.href = '../login.php';</script>";
    exit;
}

$db = DatabaseConnection::getInstance();
$conn = $db->getConnection();

$empId = $_SESSION['user_id'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id']) && isset($_POST['status'])) {
    $taskId = $_POST['task_id'];
    $status = $_POST['status'];

    // Update task status
    $updateStmt = $conn->prepare("UPDATE task SET status = ? WHERE id = ?");
    $updateStmt->bind_param("si", $status, $taskId);
    $updateStmt->execute();

    // Fetch task details
    $taskDetail = $conn->prepare("SELECT task, room_number FROM task WHERE id = ?");
    $taskDetail->bind_param("i", $taskId);
    $taskDetail->execute();
    $taskResult = $taskDetail->get_result();

    if ($taskResult && $taskResult->num_rows > 0) {
        $taskData = $taskResult->fetch_assoc();
        $taskName = $taskData['task'];
        $roomNumber = $taskData['room_number'];

        //  If task is Room Service, update progress_status for matching request
        if (strtolower($taskName) === 'room service' && !empty($roomNumber)) {
            $updateService = $conn->prepare("
                UPDATE room_service_requests 
                SET progress_status = ? 
                WHERE room_number = ? AND status = 'Approved'
            ");
            $updateService->bind_param("ss", $status, $roomNumber);
            $updateService->execute();
        }
    }

    echo "<script>alert('Task status updated successfully!'); window.location.href = 'mytask.php';</script>";
    exit;
}

//  Fetch employee's tasks
$stmt = $conn->prepare("SELECT id, task, task_description, start_time, end_time, room_number, status FROM task WHERE emp_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $empId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Tasks</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar {
      background-color: #003366 !important;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      padding: 12px;
    }

    .container {
      margin-top: 80px;
    }

    .btn-back {
      background-color: #ffffff;
      color: #003366;
      border: 1px solid #003366;
    }
    .btn-back:hover {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
    <div class="ms-auto">
      <a href="e_dashboard.php" class="btn btn-sm btn-back">⬅ Back to Dashboard</a>
    </div>
  </div>
</nav>

<div class="container">
  <h2 class="text-center text-primary mb-4">My Assigned Tasks</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Task</th>
            <th>Description</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Room Number</th>
            <th>Status</th>
            <th>Update Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['task']) ?></td>
            <td><?= htmlspecialchars($row['task_description']) ?></td>
            <td><?= htmlspecialchars($row['start_time']) ?></td>
            <td><?= htmlspecialchars($row['end_time']) ?></td>
            <td><?= htmlspecialchars($row['room_number']) ?: 'N/A' ?></td>
            <td><?= htmlspecialchars($row['status']) ?: 'Not Started' ?></td>
            <td>
              <form action="" method="POST">
                <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                <select class="form-select form-select-sm" name="status" required>
                  <option value="" disabled selected>Update Status</option>
                  <option value="In Progress" <?= ($row['status'] == 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                  <option value="Completed" <?= ($row['status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
                </select>
                <button type="submit" class="btn btn-sm btn-warning mt-2">Update</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-center text-muted">No tasks assigned yet.</p>
  <?php endif; ?>
</div>

</body>
</html>
