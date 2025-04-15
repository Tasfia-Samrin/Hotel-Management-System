<?php
class User {
    protected $id;
    protected $email;
    protected $password;
    protected $role;
    protected $contact_number;
    protected $created_at;
    protected $address;
    protected $name;

    // Constructor to initialize the user object
    public function __construct($id = null, $email = null, $password = null, $role = null, $contact_number = null, $created_at = null, $address = null, $name = null) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->contact_number = $contact_number;
        $this->created_at = $created_at;
        $this->address = $address;
        $this->name = $name;
    }

    // get methods
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    public function getContactNumber() { return $this->contact_number; }
    public function getCreatedAt() { return $this->created_at; }
    public function getAddress() { return $this->address; }
    public function getName() { return $this->name; }

    // Abstract method to be implemented by each role (to return the dashboard page)
    public function getDashboard() {
        return "";
    }

    // Authenticate user and retrieve details from the database
    public static function authenticate($email, $password) {
        $conn = DatabaseConnection::getInstance()->getConnection();
        
        //  SQL query
        $stmt = $conn->prepare("SELECT id, email, password, role, contact_number, created_at, address, name FROM users WHERE email = ?");
        
        if (!$stmt) {
            throw new Exception("Database query preparation failed.");
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        $id = $db_email = $db_password = $role = $contact_number = $created_at = $address = $name = "";
        
        //  Ensure data exists before binding variables
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_email, $db_password, $role, $contact_number, $created_at, $address, $name);
            $stmt->fetch();

            // password hashing 
            if (password_verify($password, $db_password)) {
                
                
                    // factory pattern to return the correct role-based object
                    return RoleFactory::createUser($id, $db_email, $db_password, $role, $contact_number, $created_at, $address, $name);
            
            } else {
                throw new Exception("Invalid credentials.");
            }
        } else {
            throw new Exception("No user found with this email.");
        }

        $stmt->close();
    }

    
    public function setName($name) {
        $this->name = $name;
    }

    public function setContactNumber($contact_number) {
        $this->contact_number = $contact_number;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
}
?>
