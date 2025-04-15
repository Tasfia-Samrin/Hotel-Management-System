<?php
session_start();
require_once '../../DatabaseConnection.php';
$conn = DatabaseConnection::getInstance()->getConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Booking Management</title>
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
    .btn-back {
      background-color: #ffffff;
      color: #003366;
      border: 1px solid #003366;
    }
    .btn-back:hover {
      background-color: #f0f0f0;
    }
    body {
      padding-top: 80px;
    }
    .action-buttons a {
      margin-right: 5px;
    }
  </style>
</head>
<body>

<!--  Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Sunrise Hotel</a>
    <div class="ms-auto">
      <a href="../admin_dashboard.php" class="btn btn-sm btn-back">⬅ Back to Dashboard</a>
    </div>
  </div>
</nav>

<!--  Page Content -->
<div class="container">
  <h2 class="text-center text-primary mb-4">Booking Management</h2>

  <!--  Search -->
  <div class="mb-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by guest name...">
  </div>

  <!--  Booking Table -->
  <table class="table table-bordered table-hover" id="bookingTable">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Guest</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Room</th>
        <th>Room Type</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Guests</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $query = "
        SELECT 
            b.id, b.guests, b.checkin_date, b.checkout_date, b.status, 
            u.name AS guest_name, u.email, u.contact_number, 
            r.room_number, rt.type_name AS room_type
        FROM booking b
        JOIN users u ON b.guest_id = u.id
        JOIN rooms r ON b.room_id = r.id
        LEFT JOIN room_types rt ON r.room_type_id = rt.id
        ORDER BY b.created_at DESC
    ";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()):
        $status = strtolower($row['status']);
        $badgeClass = match ($status) {
            'confirmed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default     => 'bg-warning'
        };
    ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['guest_name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['contact_number']) ?></td>
        <td><?= htmlspecialchars($row['room_number']) ?></td>
        <td><?= htmlspecialchars($row['room_type'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($row['checkin_date']) ?></td>
        <td><?= htmlspecialchars($row['checkout_date']) ?></td>
        <td><?= $row['guests'] ?></td>
        <td><span class="badge <?= $badgeClass ?>"><?= $row['status'] ?></span></td>
        <td class="action-buttons">
          <?php
            switch ($status) {
              case 'pending':
                echo '<a href="approve_booking.php?id=' . $row['id'] . '" class="btn btn-success btn-sm">Approve</a>';
                echo '<a href="cancel_booking.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm">Cancel</a>';
                break;
              case 'confirmed':
                echo '<a href="cancel_booking.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Cancel</a>';
                break;
              case 'cancelled':
                echo '<a href="approve_booking.php?id=' . $row['id'] . '" class="btn btn-success btn-sm">Re-Approve</a>';
                break;
              default:
                echo '<span class="text-muted">No actions</span>';
            }
          ?>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!--  Search + Confirmation Script -->
<script>
  document.getElementById("searchInput").addEventListener("keyup", function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll("#bookingTable tbody tr");
    rows.forEach(row => {
      const guestName = row.cells[1].textContent.toLowerCase();
      row.style.display = guestName.includes(searchValue) ? "" : "none";
    });
  });

  document.querySelectorAll('.action-buttons a').forEach(button => {
    button.addEventListener('click', function (e) {
      const action = this.textContent.trim();
      const confirmMsg = `Are you sure you want to ${action.toLowerCase()} this booking?`;
      if (!confirm(confirmMsg)) {
        e.preventDefault();
      }
    });
  });
</script>

</body>
</html>
