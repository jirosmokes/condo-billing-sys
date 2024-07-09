-- Create dorm_hub_db Database
CREATE DATABASE dorm_hub_db;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(255) NOT NULL,
  `account_password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `emergency_number` varchar(20) NOT NULL,
  `access_lvl` varchar(20) NOT NULL DEFAULT 'user',
  `room_number` int(11) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_number` (`room_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `billing_information`
CREATE TABLE `billing_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_number` int(11) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `expiry` varchar(5) NOT NULL,
  `cvc` varchar(4) NOT NULL,
  `country` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(20) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payer_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_number` (`room_number`),
  CONSTRAINT `billing_information_ibfk_1` FOREIGN KEY (`room_number`) REFERENCES `users` (`room_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `transactions`
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_number` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `status` enum('paid','pending') NOT NULL DEFAULT 'pending',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_number` (`room_number`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`room_number`) REFERENCES `users` (`room_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` 
(account_number, account_password, first_name, middle_name, last_name, school, contact_number, emergency_number, access_lvl, room_number, profile_picture)
VALUES 
('admin1', 'admin1', 'Dennis', '', 'Lappay', 'Dormhub Admin', '12345678', '12345678', 'admin', 1, '');

INSERT INTO `users` 
(account_number, account_password, first_name, middle_name, last_name, school, contact_number, emergency_number, access_lvl, room_number, profile_picture)
VALUES 
('admin2', 'admin2', 'Dustine', '', 'Wania', 'Dormhub Admin', '12345678', '12345678', 'admin', 1, '');

INSERT INTO `users` 
(account_number, account_password, first_name, middle_name, last_name, school, contact_number, emergency_number, access_lvl, room_number, profile_picture)
VALUES 
('admin3', 'admin3', 'Exequiel', '', 'Bangal', 'Dormhub Admin', '12345678', '12345678', 'admin', 1, '');

INSERT INTO `users` 
(account_number, account_password, first_name, middle_name, last_name, school, contact_number, emergency_number, access_lvl, room_number, profile_picture)
VALUES 
('admin4', 'admin4', 'Jabenn', '', 'Tinio', 'Dormhub Admin', '12345678', '12345678', 'admin', 1, '');

