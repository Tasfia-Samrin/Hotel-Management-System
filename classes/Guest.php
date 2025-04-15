<?php
require_once "User.php";

class Guest extends User {
    public function __construct($id, $email, $password, $role, $contact_number, $created_at, $address, $name) {
        parent::__construct($id, $email, $password, $role, $contact_number, $created_at, $address, $name);
    }
    public function getDashboard() {
        return "guest/g_dashboard.php";
    }
}
?>
