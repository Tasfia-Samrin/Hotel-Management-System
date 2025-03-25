<?php
class DatabaseConnection {
    private static $instance = null;
    private $conn;

    // Private constructor to prevent creating multiple instances
    private function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'hotel_management_system');  // Adjust credentials if needed
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Get the instance of the connection (Singleton)
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    // Get the connection object
    public function getConnection() {
        return $this->conn;
    }
}
?>