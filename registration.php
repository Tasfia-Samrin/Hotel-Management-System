<?php
session_start();
require_once "role_factory.php";
require_once "DatabaseConnection.php";

$errorMsg = '';
$successMsg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $contact_number = $_POST['contact_number'];
        $address = $_POST['address'];

        // Password length validation
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $conn = DatabaseConnection::getInstance()->getConnection();
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, contact_number, address) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $hashed_password, $role, $contact_number, $address);
        $stmt->execute();
        $stmt->close();

        $user = RoleFactory::createUser(null, $email, $hashed_password, $role, $contact_number, null, $address, $name);
        $_SESSION['user'] = $user;

        $successMsg = "Registration successful! Redirecting to login page...";
        header("refresh:3;url=login.php"); 
    } catch (Exception $e) {
        $errorMsg = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Sunrise Hotel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color:#003366;
        }
        .card {
            max-width: 500px;
            margin: 80px auto;
            padding: 20px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .btn-primary {
            width: 100%;
        }

        .custom-login-btn {
    background-color: #003366;
    color: white;
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.custom-login-btn:hover {
    background-color:rgb(39, 108, 177);
}
    </style>
</head>
<body>

<!-- Navbar  -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="#">Sunrise Hotel</a>
        <div class="ml-auto">
            <a href="login.php" class="btn btn-light btn-sm mr-2">Login</a>
            <a href="registration.php" class="btn btn-outline-light btn-sm">Register</a>
        </div>
    </div>
</nav>


<div style="height: 70px;"></div>

<!-- Registration Form -->
<div class="card">
    <h3 class="text-center mb-4">Create Your Account</h3>

    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
    <?php endif; ?>
    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php endif; ?>

    <form method="POST" action="registration.php">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password (min 8 characters)</label>
            <input type="password" name="password" class="form-control" required minlength="8">
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="employee">Employee</option>
                <option value="guest">Guest</option>
            </select>
        </div>
        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <p class="text-center mt-3">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </form>
</div>

</body>
</html>
