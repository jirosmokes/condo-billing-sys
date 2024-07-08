<?php
require '../connection-db.php'; 
include 'admin-room-details.php'; 
session_start();
if (empty($_SESSION['account_number'])) {
    header("Location: ../landing-page.php");
    exit();
}
$rooms = [];
$sql = "SELECT room_number FROM users WHERE access_lvl = 'user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $roomnumber = $_POST['roomnumber'];

    $delete_sql = "DELETE FROM users WHERE room_number = '$roomnumber'";
    if ($conn->query($delete_sql) === TRUE) {
        $message = "Record deleted successfully";
        // Refresh the rooms list after deletion
        $rooms = [];
        $sql = "SELECT room_number FROM users WHERE status = 'user'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rooms[] = $row;
            }
        }
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin-style/admin-delete-user.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../images/dorm-hub-logo-official-2.png" type="image/png">
    <title>Delete User</title>
</head>
<body>
    <div class="container">
        <a href="../admin-side/admin-room-selection.php"><i class="fa-solid fa-arrow-left"></i></a>    
        <h2>Delete</h2>
        <?php if (!empty($message)): ?>
            <div class="message">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        <hr>
        <form action="" method="post">
            <div class="form-group">
                <label for="roomnumber">Room Number:</label>
                <select name="roomnumber" id="roomnumber" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['room_number']; ?>"><?php echo $room['room_number']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="submit">Delete</button>
        </form>
    </div>
</body>
</html>
