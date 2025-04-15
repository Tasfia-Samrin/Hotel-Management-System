<?php
session_start();
require_once __DIR__ . '/../../DatabaseConnection.php';

$db = DatabaseConnection::getInstance();
$conn = $db->getConnection();

// Task class
class Task {
    public $empId;
    public $taskName;
    public $description;
    public $startTime;
    public $endTime;
    public $roomNumber;
}

class TaskBuilder {
    private $task;

    public function __construct() {
        $this->task = new Task();
    }

    public function setEmpId($empId) {
        $this->task->empId = $empId;
        return $this;
    }

    public function setTaskName($taskName) {
        $this->task->taskName = $taskName;
        return $this;
    }

    public function setDescription($description) {
        $this->task->description = $description;
        return $this;
    }

    public function setStartTime($startTime) {
        $this->task->startTime = $startTime;
        return $this;
    }

    public function setEndTime($endTime) {
        $this->task->endTime = $endTime;
        return $this;
    }

    public function setRoomNumber($roomNumber) {
        $this->task->roomNumber = $roomNumber;
        return $this;
    }

    public function build() {
        return $this->task;
    }
}

// Fetch room numbers
$roomResult = $conn->query("SELECT room_number FROM rooms");
$rooms = [];
if ($roomResult && $roomResult->num_rows > 0) {
    while ($roomRow = $roomResult->fetch_assoc()) {
        $rooms[] = $roomRow['room_number'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $builder = new TaskBuilder();
    $task = $builder->setEmpId($_POST['empId'] ?? '')
                    ->setTaskName($_POST['newDuty'] ?? '')
                    ->setDescription($_POST['description'] ?? '')
                    ->setStartTime($_POST['start_time'] ?? '')
                    ->setEndTime($_POST['end_time'] ?? '')
                    ->setRoomNumber($_POST['room_number'] ?? null)
                    ->build();

    if (!empty($task->empId) && !empty($task->taskName) && !empty($task->startTime) && !empty($task->endTime)) {
        $stmt = $conn->prepare("INSERT INTO task (emp_id, task, task_description, start_time, end_time, room_number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $task->empId, $task->taskName, $task->description, $task->startTime, $task->endTime, $task->roomNumber);

        if ($stmt->execute()) {
            echo "<script>alert('Task assigned successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        } else {
            echo "<script>alert('Failed to assign task.'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Employees</title>
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
    body {
      padding-top: 80px;
    }
    .input-group-sm .form-control,
    .input-group-sm .form-select {
      font-size: 0.85rem;
    }
    .form-inline-fields {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      align-items: center;
    }
    .task-history {
      max-height: 200px;
      overflow-y: auto;
      scrollbar-width: thin;
    }
    .task-history::-webkit-scrollbar {
      width: 6px;
    }
    .task-history::-webkit-scrollbar-thumb {
      background-color: rgba(0,0,0,0.2);
      border-radius: 4px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand fw-bold text-white" href="#">Sunrise Hotel</a>
    <a href="../admin_dashboard.php" class="btn btn-light btn-sm">Back to Dashboard</a>
  </div>
</nav>

<div class="container my-5">
  <h2 class="text-center text-primary mb-4">Manage Employees</h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Task History</th>
          <th>Assign New Duty</th>
        </tr>
      </thead>
      <tbody>
      <?php 
      $result = $conn->query("SELECT id, name, email, contact_number FROM users WHERE role = 'employee'");
      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
          $empId = $row['id'];
      ?>
      <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['contact_number']) ?></td>
        <td>
          <?php
            //  ORDER BY id DESC ensures latest task comes first
            $taskQuery = $conn->query("SELECT task, task_description, start_time, end_time, room_number, status FROM task WHERE emp_id = $empId ORDER BY id DESC");

            if ($taskQuery && $taskQuery->num_rows > 0) {
                echo "<div class='task-history'>";
                while ($taskRow = $taskQuery->fetch_assoc()) {
                    $status = $taskRow['status'] ?? 'Not Started';
                    $badgeClass = match ($status) {
                        'Completed' => 'bg-success',
                        'In Progress' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                    };

                    echo "<div class='border rounded p-2 mb-2'>";
                    echo "<span class='badge $badgeClass mb-1'>$status</span><br>";
                    echo "<strong>Task:</strong> " . htmlspecialchars($taskRow['task']) . "<br>";
                    echo "<strong>Description:</strong> " . htmlspecialchars($taskRow['task_description']) . "<br>";
                    echo "<strong>Room:</strong> " . (!empty($taskRow['room_number']) ? htmlspecialchars($taskRow['room_number']) : 'N/A') . "<br>";
                    echo "<strong>From:</strong> " . date("M d, Y H:i", strtotime($taskRow['start_time'])) . "<br>";
                    echo "<strong>To:</strong> " . date("M d, Y H:i", strtotime($taskRow['end_time'])) . "</div>";
                }
                echo "</div>";
            } else {
                echo "No tasks assigned.";
            }
          ?>
        </td>
        <td>
          <form action="" method="POST" class="form-inline-fields">
            <input type="hidden" name="empId" value="<?= $row['id'] ?>">
            <select class="form-select form-select-sm" name="newDuty" required>
              <option value="" selected disabled>Duty</option>
              <option value="Room Service">Room Service</option>
              <option value="Reception">Reception</option>
              <option value="Maintenance">Maintenance</option>
            </select>
            <select class="form-select form-select-sm" name="room_number">
              <option value="" selected disabled>Room No (optional)</option>
              <?php foreach ($rooms as $roomNum): ?>
                <option value="<?= htmlspecialchars($roomNum) ?>"><?= htmlspecialchars($roomNum) ?></option>
              <?php endforeach; ?>
            </select>
            <input type="text" name="description" class="form-control form-control-sm" placeholder="Description" required>
            <input type="datetime-local" name="start_time" class="form-control form-control-sm" required>
            <input type="datetime-local" name="end_time" class="form-control form-control-sm" required>
            <button type="submit" class="btn btn-success btn-sm">Assign</button>
          </form>
        </td>
      </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="6" class="text-center">No employee records found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
