<?php
require 'connection-db.php';

function displayRoomStatus($conn, $room_number)
{
    $room_number = mysqli_real_escape_string($conn, $room_number);

    $check_existing_sql = "SELECT * FROM users WHERE room_number = '$room_number'";
    $result = $conn->query($check_existing_sql);

    if ($result->num_rows > 0) {
        echo '<i class="fa-solid fa-door-closed" style="font-size: 2em"></i>';
    } else {
        echo '<i class="fa-solid fa-door-open" style="font-size: 2em"></i>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Room Selection</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="icon" href="images/dorm-hub-logo-official-2.png" type="image/png">
    <?php require 'admin-side/admin-room-details.php'; ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: rgb(39, 40, 41);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            position: relative;
            width: 1000px;
            margin: 50px 20px;
            /* margin-top: 100px;
	margin-bottom: 100px; */
            /* max-width: 500px; */
            min-height: 100px;
            background: rgb(24, 25, 26);
            display: grid;
            grid-template-columns: repeat(auto-fill,
                    minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        .room-container {
            background-color: rgb(170, 254, 2);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 16px;
        }

        .room-content {
            text-align: center;
        }

        .room-content h2 {
            font-size: 20px;
            margin-top: 8px;
            font-weight: 1000;
        }

        .room-content p {
            color: black;
            font-size: 15px;
            line-height: 1.3;
            margin-top: 5px;
            margin-bottom: 10px;
            font-weight: 1000;
        }

        .room-content button {
            width: 60px;
            height: 50px;
            padding: 10px;
            background-color: black;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 10px;
            cursor: pointer;
            font-weight: 100;
            margin: 5px 0;
        }

        .room-content button:hover {
            background-color: rgb(46, 46, 46);
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px 10px;
                padding: 15px;
            }

            .room-content h2 {
                font-size: 18px;
            }

            .room-content p {
                font-size: 14px;
            }

            .room-content button {
                width: 50px;
                height: 40px;
                font-size: 8px;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px 5px;
                padding: 10px;
            }

            .room-content h2 {
                font-size: 16px;
            }

            .room-content p {
                font-size: 12px;
            }

            .room-content button {
                width: 45px;
                height: 35px;
                font-size: 7px;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="tel"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .name-group {
            display: flex;
            gap: 10px;
        }

        .name-group .form-group {
            flex: 1;
        }

        hr {
            margin-top: 19px;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 15px;
            background-color: rgb(170, 254, 2);
            color: rgb(24, 25, 26);
            font-size: 16px;
            cursor: pointer;
            font-weight: 500;
        }

        button:hover {
            background-color: rgb(187, 249, 64);
        }

        .buttons {
            background-color: rgb(24, 25, 26);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 16px;
        }

        .buttons button {
            width: 80px;
            height: 50px;
            margin: 5px 5px;
            border-radius: 2px;
        }

        .error {
            align-content: center;
            width: 100%;
        }

        .error p {
            width: 100%;
            background-color: #fff;
            background: rgb(249, 64, 64);
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 3px;
            color: #ffffff;
        }

        .dd {
            text-align: left;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php foreach ($rooms as $room) : ?>
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