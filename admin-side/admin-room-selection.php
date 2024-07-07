<?php
require '../connection-db.php';

function displayRoomStatus($conn, $room_number) {
    $room_number = mysqli_real_escape_string($conn, $room_number);

    $check_existing_sql = "SELECT * FROM users WHERE room_number = '$room_number'";
    $result = $conn->query($check_existing_sql);

    if ($result->num_rows > 0) {
        echo '<i class="fa-solid fa-lock"></i>';
    } else {
        echo '<i class="fa-solid fa-unlock-keyhole"></i>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['add'])) {
        header('Location: ../admin-side/admin-add-user.php');
        exit();
    } elseif (isset($_POST['update'])) {
        header('Location: ../admin-side/admin-update-user.php');
        exit();
    } elseif (isset($_POST['delete'])) {
        header('Location: ../admin-side/admin-delete-user.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin-style/admin-sidebar.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="../admin-style/admin-room-selection.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Room Selection</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="icon" href="../images/dorm-hub-logo-official-2.png" type="image/png">
    <?php require 'admin-room-details.php'; ?>

    <style>
        /* Content styles */
        #content {
            margin-left: 250px;
            padding: 20px;
            color: white;
        }

        body {
            margin: 0;
            font-family: "Open Sans", sans-serif;
            background-color: #1e1e1e;
        }
    </style>
</head>
<body>
  
    <div class="sidebar">
        <header><img src="../images/dorm-hub-logo-official.png" alt="" height="30px"></header>

        <a href="../admin-side/admin-dashboard.php" class="active" onclick="showContent('dashboard')">
            <i class="fas fa-qrcode"></i>
            <span>Dashboard</span>
        </a>

        <a href="../admin-side/admin-room-selection.php" onclick="showContent('view-tenants')">
            <i class="fa-solid fa-users-viewfinder"></i>
            <span>View Tenants</span>
        </a>

        <a href="../admin-side/admin-transaction.php">
        <i class="fas fa-money-bill-transfer"></i>
        <span>Create Bills</span>
         </a>
         
        <form method="post" action="../logout.php   ">
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
  
    <form method="post">
        <div class="buttons">
            <button type="submit" name="add"><i class="fa-solid fa-user-plus"></i></button>
            <button type="submit" name="update"><i class="fa-solid fa-user-pen"></i></button>
            <button type="submit" name="delete"><i class="fa-solid fa-user-minus"></i></button>
        </div>
    </form>
    <div class="container">
        <?php foreach($rooms as $room): ?>
            <div class="room-container">
                <div class="room-content">
                    <h2><?php echo $room['room_number']; ?></h2>
                    <p>Capacity: <?php echo $room['room_capacity']; ?></p>
                    <?php displayRoomStatus($conn, $room['room_number']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
