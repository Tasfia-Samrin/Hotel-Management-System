<?php
require_once "classes/User.php";
require_once "classes/Admin.php";
require_once "classes/Employee.php";
require_once "classes/Guest.php";

class RoleFactory {
    public static function createUser($id, $email, $password, $role, $contact_number, $created_at, $address, $name) {
        switch ($role) {
            case 'admin':
                return new Admin($id, $email, $password, $role, $contact_number, $created_at, $address, $name);
            case 'employee':
                return new Employee($id, $email, $password, $role, $contact_number, $created_at, $address, $name);
            case 'guest':
                return new Guest($id, $email, $password, $role, $contact_number, $created_at, $address, $name);
            default:
                throw new Exception("Invalid role selected.");
        }
    }
}
?>