-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 05:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `guests` int(11) NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `status` enum('available','booked','maintenance') NOT NULL DEFAULT 'available',
  `room_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `price`, `amenities`, `status`, `room_type_id`) VALUES
(19, '101', 1600.00, 'Wifi,Ac,Tv', 'available', 1),
(20, '102', 1900.00, 'Coffee Maker, Smart TV, Free Wi-Fi, Queen Bed', 'available', 1),
(21, '103', 1600.00, 'Wifi,Ac,Tv', 'available', 1),
(22, '104', 2500.00, 'Partial Ocean View, Balcony, Smart TV, Queen Bed', 'available', 2),
(23, '105', 2500.00, 'Partial Ocean View, Balcony, Smart TV, Queen Bed', 'available', 2),
(24, '106', 2500.00, 'Partial Ocean View, Balcony, Smart TV, Queen Bed', 'available', 2),
(25, '107', 4000.00, 'Coffee Maker, Smart TV, Free Wi-Fi, King Bed, Luxury Bedding, Mini Bar, Ocean view', 'available', 3),
(26, '108', 4000.00, 'Coffee Maker, Smart TV, Free Wi-Fi, King Bed, Luxury Bedding, Mini Bar', 'available', 3),
(28, '109', 3500.00, 'Coffee Maker, Smart TV, Free Wi-Fi, King Bed, Luxury Bedding, Mini Bar', 'available', 3);

-- --------------------------------------------------------

--
-- Table structure for table `room_service_requests`
--

CREATE TABLE `room_service_requests` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `item_category` varchar(50) NOT NULL,
  `item_name` text NOT NULL,
  `description` text DEFAULT NULL,
  `requested_at` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending',
  `handled_by` int(11) DEFAULT NULL,
  `handled_at` datetime DEFAULT NULL,
  `progress_status` varchar(50) DEFAULT 'Not Started'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `type_name`) VALUES
(1, 'Standard'),
(2, 'Deluxe'),
(3, 'Luxury');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `assigned_at` datetime DEFAULT current_timestamp(),
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `task_description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `room_number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `emp_id`, `task`, `assigned_at`, `start_time`, `end_time`, `task_description`, `status`, `room_number`) VALUES
(13, 33, 'Reception', '2025-04-15 21:23:58', '2025-04-15 21:30:00', '2025-04-15 12:30:00', 'Work on overall hotel maintainance', 'In Progress', NULL),
(14, 34, 'Room Service', '2025-04-15 21:25:32', '2025-04-15 22:30:00', '2025-04-16 00:30:00', 'Behave nicely', 'In Progress', '101');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('guest','employee','admin') NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `duty` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `contact_number`, `address`, `created_at`, `duty`) VALUES
(31, 'gias', 'gias@gmail.com', '$2y$10$93m1z8VukZhOtAHQUqMydOJ9mnhphGlOuvdpmRRwaltoe17usYSA.', 'admin', '076337899', 'Dhaka,Rampura', '2025-04-15 14:26:41', NULL),
(33, 'pial', 'pial@gmail.com', '$2y$10$KW/h.RUtSLtN0tWg90nMHOtglUJjuK5cw7ooU33qPOHqQpoM4pNHO', 'employee', '123456789', 'Banasree', '2025-04-15 15:20:37', NULL),
(34, 'luis', 'luis@gmail.com', '$2y$10$lCxpWD8KNL4gYwtvsb4lFubJHsFLpS7HnyG6yBX1Vqxuh4phg4cPC', 'employee', '12345678', 'Dhaka', '2025-04-15 15:22:39', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_number` (`room_number`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `room_service_requests`
--
ALTER TABLE `room_service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `handled_by` (`handled_by`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_task_room` (`room_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `room_service_requests`
--
ALTER TABLE `room_service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_service_requests`
--
ALTER TABLE `room_service_requests`
  ADD CONSTRAINT `room_service_requests_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_service_requests_ibfk_2` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk_task_room` FOREIGN KEY (`room_number`) REFERENCES `rooms` (`room_number`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
