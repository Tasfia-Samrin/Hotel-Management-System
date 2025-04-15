<?php
require_once '../DatabaseConnection.php';
require_once '../classes/User.php';
require_once '../classes/Admin.php';
require_once '../role_factory.php';

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$adminId = $_SESSION['user']->getId();
$db = DatabaseConnection::getInstance()->getConnection();

$successMsg = $errorMsg = "";
$name = $email = $contact = $address = "";

// Fetch admin info
$stmt = $db->prepare("SELECT name, email, contact_number, address FROM users WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$stmt->bind_result($name, $email, $contact, $address);
$stmt->fetch();
$stmt->close();

// Update profile
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_info'])) {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_contact = $_POST['contact'];
    $new_address = $_POST['address'];

    $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, contact_number = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $new_name, $new_email, $new_contact, $new_address, $adminId);

    if ($stmt->execute()) {
        $successMsg = "Profile updated successfully!";
        $name = $new_name;
        $email = $new_email;
        $contact = $new_contact;
        $address = $new_address;
    } else {
        $errorMsg = "Failed to update profile.";
    }
    $stmt->close();
}

// Change password
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['change_password'])) {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $stmt->bind_result($db_pass);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current, $db_pass)) {
        $errorMsg = "Current password is incorrect.";
    } elseif (strlen($new) < 8) {
        $errorMsg = "Password must be at least 8 characters long.";
    } elseif ($new !== $confirm) {
        $errorMsg = "New passwords do not match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $adminId);
        
        if ($stmt->execute()) {
            $successMsg = "Password updated successfully!";
        } else {
            $errorMsg = "Failed to update password. Please try again.";
        }
        $stmt->close();
    }
}

// Delete account
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_account'])) {
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $adminId);
    
    if ($stmt->execute()) {
        session_destroy();
        header("Location: ../home.php");
        exit();
    } else {
        $errorMsg = "Failed to delete account. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    html, body {
      background-color: #f8f9fa;
      height: 100%;
    }
    .navbar {
  background-color: #003366 !important;
  padding-top: 0;
  padding-bottom: 0;
  height: 70px;
}
    .navbar-brand {
      font-weight: bold;
    }
    .container {
      margin-top: 70px;
    }
    .card {
      max-width: 700px;
      margin: 80px auto;
      padding: 30px 40px;
      border: none;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      background-color: white;
    }
    .btn-primary {
      width: 100%;
    }
  </style>
</head>
<body>

<!--Navbar  -->
<nav class="navbar navbar-dark" style="background-color: #003366; height: 70px; padding-top: 0; padding-bottom: 0;">
  <div class="container d-flex align-items-center justify-content-between h-100" style="margin-top: -5px;">
    <a class="navbar-brand fw-bold mb-0" href="#" style="line-height: 1;">Sunrise Hotel</a>
    <a href="admin_dashboard.php" class="btn btn-light btn-sm mb-0">← Back to Dashboard</a>
  </div>
</nav>
<!-- Profile Form -->
<div class="container">
  <div class="card">
    <h3 class="text-center mb-4">👋 Welcome, <?php echo htmlspecialchars($name); ?></h3>

    <?php if ($successMsg): ?>
      <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php elseif ($errorMsg): ?>
      <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <!-- Admin Info -->
    <form method="POST">
      <h5 class="mb-3">Your Profile</h5>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
      </div>
      <div class="mb-3">
        <label class="form-label">Contact Number</label>
        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" readonly>
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" readonly>
      </div>

      <!-- Buttons -->
      <div class="btn-group w-100 gap-2 mt-3">
        <button type="button" class="btn btn-outline-primary" onclick="enableEdit()">Edit</button>
        <button type="submit" name="update_info" class="btn btn-success">Save</button>
      </div>
    </form>

    <hr class="my-4">

    <!-- Password Update -->
    <form method="POST" id="passwordForm">
      <h5 class="mb-3">Change Password</h5>
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new" class="form-control" required minlength="8">
        <small class="text-muted">Password must be at least 8 characters long</small>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm" class="form-control" required minlength="8">
      </div>
      <button type="submit" name="change_password" class="btn btn-warning w-100">Update Password</button>
    </form>
    <hr class="my-4">

    <!-- Delete Account -->
    <form method="POST" id="deleteForm">
      <button type="submit" name="delete_account" class="btn btn-danger w-100">Delete My Account</button>
    </form>
  </div>
</div>

<!-- enable editing -->
<script>
  function enableEdit() {
    ['name', 'email', 'contact', 'address'].forEach(id => {
      document.getElementById(id).removeAttribute('readonly');
    });
  }

  // Password form validation
  document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPass = e.target.querySelector('[name="new"]').value;
    const confirmPass = e.target.querySelector('[name="confirm"]').value;
    
    if (newPass.length < 8) {
      alert('Password must be at least 8 characters long.');
      e.preventDefault();
      return false;
    }
    
    if (newPass !== confirmPass) {
      alert('New passwords do not match.');
      e.preventDefault();
      return false;
    }
  });

  // Delete account confirmation
  document.getElementById('deleteForm').addEventListener('submit', function(e) {
    if (!confirm('Are you sure you want to delete your account? This cannot be undone.')) {
      e.preventDefault();
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>