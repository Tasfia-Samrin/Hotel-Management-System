<?php
session_start();
include 'admin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $admin = new Admin();
    $loginSuccess = $admin->login($email, $password);

    if ($loginSuccess) {
        $_SESSION['admin'] = $email;
        header("Location: a_index.php"); // Redirect to the dashboard or any page you want
        exit();
    } else {
        echo "<h3>Invalid Email or Password.</h3>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        h2 {
            margin-top: 20px;
        }

        .centered-image {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px; /* Adjust as needed */
            margin-bottom: 30px;
        }

        .centered-image img {
            width: 500px; /* Set a fixed width */
            height: auto; /* Maintain aspect ratio */
        }

        p {
            font-size: 14px;
            margin-top: 10px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-5"> Login To Your Account!</h2>
        <div class="row d-flex justify-content-center">
            <!-- Image Section -->
            <div class="col-lg-6 col-xl-5">
                <img src="../image/registration.jpg" alt="Customer Registration" class="img-fluid">
            </div>

            <!-- Form Section -->
            <div class="col-lg-6 col-xl-5">
                <div class="form-container">
                    <form action="" method="POST">
                        
                        <!-- Email Field -->
                        <div class="form-outline mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email"
                                required class="form-control">
                        </div>

                        <!-- Password Field -->
                        <div class="form-outline mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter your password"
                                required class="form-control">
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary btn-block" name="customer_login" value="Login">
                            <!-- Text and Link to Login Page -->
                            <p class="mt-3">
                                Do no have an account? 
                                <a href="customer_registration.php" class="text-primary">Register</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>