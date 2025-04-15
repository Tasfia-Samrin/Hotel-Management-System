<?php
require_once __DIR__ . "/User.php";

class Admin extends User {
    public function __construct($id, $email, $password, $role, $contact_number, $created_at, $address, $name) {
        parent::__construct($id, $email, $password, $role, $contact_number, $created_at, $address, $name);
    }

    public function getDashboard() {
        return "admin/admin_dashboard.php";
    }
    
}
?>
