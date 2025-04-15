<?php
session_start();
require_once "role_factory.php";
require_once "DatabaseConnection.php";

$errorMsg = '';
$successMsg = '';
$loggedIn = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        
        $user = User::authenticate($email, $password);
       
        // Create role-based user (Guest/Admin/Employee)
        $user = RoleFactory::createUser(
            $user->getId(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getRole(),
            $user->getContactNumber(),
            $user->getCreatedAt(),
            $user->getAddress(),
            $user->getName()
        );

        // Set session values for access control
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['role'] = $user->getRole();
        $_SESSION['user'] = $user;

        // Redirect immediately to dashboard
        header("Location: " . $user->getDashboard());
        exit;

    } catch (Exception $e) {
        $errorMsg = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Sunrise Hotel</title>
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
html, body {
    height: 100%;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.navbar {
    background-color: #003366 !important;
    position: sticky;
    top: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding-top: 0.3rem;
    padding-bottom: 0.3rem;
}

.navbar-brand {
    font-weight: bold;
    font-size: 1.25rem;
    padding-top: 0;
    padding-bottom: 0;
}

.navbar .btn {
    margin-left: 8px;
    padding: 4px 10px;
    font-size: 0.85rem;
}

.container {
    margin-top: 30px;
}

.card {
    max-width: 500px;
    margin: 40px auto;
    padding: 20px 25px;
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
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

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Sunrise Hotel</a>
        <div class="ml-auto">
            <a href="home.php" class="btn btn-light btn-sm">← Back</a>
            <a href="login.php" class="btn btn-light btn-sm">Login</a>
            <a href="registration.php" class="btn btn-outline-light btn-sm">Register</a>
        </div>
    </div>
</nav>



<!-- Login Form / Message -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <h3 class="text-center mb-4">Login To Your Account!</h3>

                <?php if (!empty($errorMsg)): ?>
                    <div class="alert alert-danger"><?= $errorMsg ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password (min 8 characters)</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                   
                    <button type="submit" class="btn custom-login-btn">Login</button>
                    <p class="text-center mt-3">
                        Don't have an account? <a href="registration.php">Register here</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
