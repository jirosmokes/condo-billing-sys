<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password
$dbname = "dorm_hub_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if users already exist
$sql_check_users = "SELECT COUNT(*) AS num_users FROM users WHERE account_number IN ('admin1', 'admin2', 'admin3', 'admin4')";
$result = $conn->query($sql_check_users);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $num_users = $row['num_users'];
    
    if ($num_users == 4) {
        // echo "Users already exist in the table<br>";
    } else {
        // Insert data into 'users' table
        $sql_insert_users = "
        INSERT INTO `users` 
        (account_number, account_password, first_name, middle_name, last_name, school, contact_number, emergency_number, access_lvl, room_number, profile_picture)
        VALUES 
        ('admin1', 'admin1', 'Dennis', '', 'Lappay', 'Dormhub Admin', '12345678', '12345678', 'admin', 1, ''),
        ('admin2', 'admin2', 'Dustin', '', 'Wania', 'Dormhub Admin', '12345678', '12345678', 'admin', 1, ''),
        ('admin3', 'admin3', 'Exequiel', '', 'Bangal', 'Dormhub Admin', '12345678', '12345678', 'admin', 1, ''),
        ('admin4', 'admin4', 'Jabenn',   '', 'Tinio', 'Dormhub Admin', '12345678', '12345678', 'admin', 1, '')";

        if ($conn->query($sql_insert_users) === TRUE) {
            // echo "Data inserted into 'users' table successfully<br>";
        } else {
            echo "Error inserting data into 'users' table: " . $conn->error;
        }
    }
} else {
    echo "Error checking existing users: " . $conn->error;
}

// Close connection
// $conn->close();
?>
