# Hotel-Management-System

**Group Members:**

Tasfia Samrin               2211249042

Riazul Zannat               2211199042

Tasin Koraiza Boishakhi     2211528042

Tayeba Hasan                2211963042




#  Sunrise Hotel Management System
A full-featured hotel management platform built with PHP, MySQL, and Object-Oriented Design Patterns, supporting guests, employees, and admins. Real-time email notifications and secure access via role-based logic make this system suitable for hotel operations.

---

##  Features
- Guests can:
  - Register & log in
  - Book available rooms
  - Pay via different methods
  - View booking status (Pending, Confirmed, Cancelled)
  - Receive email notifications for booking confirmation or cancellation
  - Request room service after booking

- Admin can:
  - Manage all bookings (approve/cancel)
  - Add, edit, delete rooms (protected by Proxy pattern)
  - Assign tasks to employees using Builder pattern
  - View guest & employee details

- Employees can:
  - View assigned tasks
  - Approve/delete room service requests

---

## Design Patterns Used
1.Singleton 
2.Factory    
3.Strategy    
4.Proxy      
5.Observer   
6.Builder                 
               
-----

##  Technologies Used

- PHP 8+
- MySQL (phpMyAdmin)
- XAMPP 
- Composer (for dependency management)
- PHPMailer (email service)
- Bootstrap 5 (for UI)

---

##  Installation & Setup
 Step 1: Prerequisites
- Install [XAMPP](https://www.apachefriends.org/)
- Install [Composer](https://getcomposer.org/)
- Enable PHP 8 or higher
 Step 2: Extract Project
Extract the ZIP into:
C:\xampp\htdocs\Hotel-Management-System
 Step 3: Start Server
- Launch XAMPP
- Start Apache and MySQL
 Step 4: Import Database
Visit `http://localhost/phpmyadmin`
 Create database `hms`
 Import `hms.sql` file provided in the project

