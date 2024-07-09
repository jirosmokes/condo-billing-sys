<?php
require '../connection-db.php';
include 'admin-room-details.php';

session_start();
if (empty($_SESSION['account_number'])) {
    header("Location: ../landing-page.php");
    exit();
}

$new_user_validation = false;
$new_user_msg = "";
if (isset($_POST["submit-add"])) {
    $account_number = $_POST["accountnumber"];
    $room_number = $_POST["roomnumber"];

    $duplicate = mysqli_query($conn, "SELECT * FROM users WHERE account_number = '$account_number' OR room_number = '$room_number'");

    if (mysqli_num_rows($duplicate) > 0) {
        $new_user_validation = true;
        $new_user_msg = "User already exists or room already taken.";
    } else {
        $check_existing_sql = "SELECT * FROM users WHERE account_number = '$account_number' AND room_number = '$room_number'";
        $result = $conn->query($check_existing_sql);
        if ($result->num_rows == 0) {
            $firstname = $_POST["firstname"];
            $middlename = $_POST["middlename"];
            $lastname = $_POST["lastname"];
            $password = $_POST["password"];
            $school = $_POST['school'];
            $contactnumber = $_POST["contact_number"];
            $emergencynumber = $_POST["emergency_number"];
            $access_lvl = "user"; 

            $stmt = $conn->prepare("INSERT INTO users (account_number, account_password, first_name, middle_name, last_name, school, contact_number, emergency_number, room_number, access_lvl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $account_number, $password, $firstname, $middlename, $lastname, $school, $contactnumber, $emergencynumber, $room_number, $access_lvl);

            if ($stmt->execute()) {
                $new_user_validation = true;
                $new_user_msg = "New user added successfully to Room $room_number.";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            $new_user_validation = false;
            $new_user_msg = "User with account number $account_number already exists in Room $room_number.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin-style/admin-add-user.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../images/dorm-hub-logo-official-2.png" type="image/png">
    <title>ADD USER</title>
</head>
<body>
    <div class="container">
        <h2>Add User</h2>
        <a href="../admin-side/admin-room-selection.php"><i class="fa-solid fa-arrow-left"></i></a>
            <?php if($new_user_validation): ?>
                <div class="message">
                    <p><?php echo $new_user_msg ?></p>
                </div>
            <?php endif; ?>
        <hr>
        <form action="" method="post">
            <div class="form-group">
                <select name="roomnumber" id="roomnumber" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['room_number']; ?>"><?php echo $room['room_number']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="name-group">
                <div class="form-group">
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" id="middlename" name="middlename" placeholder="Middle Name" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <input type="text" id="accountnumber" name="accountnumber" placeholder="Account Number" required autocomplete="off">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Password" required autocomplete="off">
            </div>
            <div class="form-group">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required autocomplete="off">
            </div>
            <div class="form-group">
                <input type="text" id="school" name="school" placeholder="School" required autocomplete="off">
            </div>
            <div class="form-group">
                <input type="tel" id="contact_number" name="contact_number" placeholder="Contact Number" required autocomplete="off">
            </div>
            <div class="form-group">
                <input type="tel" id="emergency_number" name="emergency_number" placeholder="Emergency Number" required autocomplete="off">
            </div>
            <button type="submit" name="submit-add">Add</button>
        </form>
    </div>
</body>
</html>
