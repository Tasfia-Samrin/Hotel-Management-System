<?php
include 'DatabaseConnection.php';

class Admin {
    private $conn;

    public function __construct() {
        $db = DatabaseConnection::getInstance();
        $this->conn = $db->getConnection();
    }

    // Method to handle login
    public function login($email, $password) {
        $query = "SELECT * FROM admin WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if ($password === $user['password']) {
                return true;  // Login successful
            }
        }
        return false;  // Invalid credentials
    }
}
?>
