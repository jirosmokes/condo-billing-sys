<?php

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password
$dbname = "dorm_hub_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS dorm_hub_db";
if ($conn->query($sql_create_db) === FALSE) {
    echo "Error creating database: " . $conn->error;
}

// Select the created database
$conn->select_db($dbname);

// Create 'users' table
$sql_create_users_table = "
CREATE TABLE IF NOT EXISTS `users` (
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_create_users_table) === TRUE) {
    // echo "Table 'users' created successfully<br>";
} else {
    echo "Error creating table 'users': " . $conn->error;
}

// Create 'billing_information' table
$sql_create_billing_info_table = "
CREATE TABLE IF NOT EXISTS `billing_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(255) NOT NULL,
  `room_number` int(11) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `country` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payer_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_create_billing_info_table) === TRUE) {
    // echo "Table 'billing_information' created successfully<br>";
} else {
    echo "Error creating table 'billing_information': " . $conn->error;
}

// Create 'transactions' table
$sql_create_transactions_table = "
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(255) NOT NULL,
  `room_number` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `status` enum('PAID','PENDING') NOT NULL DEFAULT 'PENDING',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_create_transactions_table) === TRUE) {
    // echo "Table 'transactions' created successfully<br>";
} else {
    echo "Error creating table 'transactions': " . $conn->error;
}

require_once 'insert-admin-data.php';

// Close connection
 // Remove this line to prevent premature closure of the connection

?>
