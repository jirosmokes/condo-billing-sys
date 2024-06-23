<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="AdminRoomSelection.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Room Selection</title>
    <link rel="icon" type="image/png" href="logo.png">
    <?php require 'Rooms.php' ?>
</head>
<body>
    <div class="container">
        <?php foreach($rooms as $room): ?>
            <div class="room-container">
                <div class="room-content">
                    <h2><?php echo $room['room_number'] ?></h2>
                    <p>Capacity: <?php echo $room['room_capacity'] ?></p>
                    <button><i class="fa-solid fa-user-plus"></i></button>
                    <button><i class="fa-solid fa-user-pen"></i></i></button>
                    <button><i class="fa-solid fa-user-minus"></i></i></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
