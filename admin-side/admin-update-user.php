<?php
require '../connection-db.php'; // Include your database connection script
include 'admin-room-details.php'; // Assuming this file defines $rooms array

// Check if form is submitted
if (isset($_POST['search'])) {
    $roomnumber = $_POST['roomnumber'];

    // Query to fetch user data based on room number
    $query = "SELECT * FROM users WHERE room_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $roomnumber);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data into an associative array
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    // Close statement and result set
    $stmt->close();
    $result->close();
}
$message = "";
$message_validation = false;
if(isset($_POST['submit-update'])) {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $school = $_POST['school'];
    $contact_number = $_POST['contact_number'];
    $emergency_number = $_POST['emergency_number'];
    $room_number = $_POST['roomnumber']; 

    // Query to update user data
    $query = "UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, school = ?, contact_number = ?, emergency_number = ? WHERE room_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssss', $firstname, $middlename, $lastname, $school, $contact_number, $emergency_number, $room_number);

    // Execute the update query
    if ($stmt->execute()) {
        $message = "User data updated successfully!";
        $message_validation = true;
    } else {
        $message = "Error updating user data. Please try again.";
        $message_validation = false;
    }

    // Close statement
    $stmt->close();
}

$roomnumber = isset($_POST['roomnumber']) ? $_POST['roomnumber'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin-style/admin-update-user.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../images/dorm-hub-logo-official-2.png" type="image/png">
    <title>UPDATE USER</title>
</head>
<body>
    <div class="container">
        <a href="../admin-side/admin-room-selection.php"><i class="fa-solid fa-arrow-left"></i></a>
        <h2>Update</h2>
        <?php if (!empty($message)): ?>
            <div class="message">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        <hr>
        <form action="" method="post">
            <div class="form-group">
            <select name="roomnumber" id="roomnumber" required>
                <option value="">Select Room</option>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['room_number']; ?>" <?php echo ($room['room_number'] == $roomnumber) ? 'selected' : ''; ?>><?php echo $room['room_number']; ?></option>
                <?php endforeach; ?>
            </select>
                <div class="button">
                    <button type="submit" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
            <hr>
            <?php if (isset($users) && !empty($users)) : ?>
                <?php foreach ($users as $user) : ?>
                <div class="name-group">
                    <div class="form-group">
                        <label for="firstname">Name:</label>
                        <input type="text" id="firstname" name="firstname" placeholder="First Name" value="<?php echo isset($user['first_name']) ? $user['first_name'] : ''; ?>" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="text" id="middlename" name="middlename" placeholder="Middle Name" value="<?php echo isset($user['middle_name']) ? $user['middle_name'] : ''; ?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="text" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo isset($user['last_name']) ? $user['last_name'] : ''; ?>" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label for="school">School:</label>
                    <input type="text" id="school" name="school" placeholder="School" value="<?php echo isset($user['school']) ? $user['school'] : ''; ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="tel" id="contact_number" name="contact_number" placeholder="Contact Number" value="<?php echo isset($user['contact_number']) ? $user['contact_number'] : ''; ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="emergency_number">Emergency Number:</label>
                    <input type="tel" id="emergency_number" name="emergency_number" placeholder="Emergency Number" value="<?php echo isset($user['emergency_number']) ? $user['emergency_number'] : ''; ?>" required autocomplete="off">
                </div>
                <button type="submit" name="submit-update">Update</button>
            <?php endforeach; ?>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
