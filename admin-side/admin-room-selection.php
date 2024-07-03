<?php
require '../connection-db.php';

$new_user_validation = false;
$new_user_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Button for Add
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
                $contactnumber = $_POST["contact_number"];
                $emergencynumber = $_POST["emergency_number"];

                $stmt = $conn->prepare("INSERT INTO users (account_number, account_password, first_name, middle_name, last_name, contact_number, emergency_number, room_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $account_number, $password, $firstname, $middlename, $lastname, $contactnumber, $emergencynumber, $room_number);
    
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
    

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin-style/admin-room-selection.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Room Selection</title>
    <link rel="icon" type="image/png" href="logo.png">
    <?php require 'admin-room-details.php' ?>
</head>
<body>
    <div class="buttons">
        <?php if($new_user_validation): ?>
            <div class="error">
                <p><?php echo $new_user_msg ?></p>
            </div>
        <?php endif; ?>            
        <button type="button" name="add"><i class="fa-solid fa-user-plus"></i></button>
        <button type="button" name="update"><i class="fa-solid fa-user-pen"></i></button>
        <button type="button" name="delete"><i class="fa-solid fa-user-minus"></i></button>
    </div>
    <div class="container">
        <?php foreach($rooms as $room): ?>
            <div class="room-container">
                <div class="room-content">
                    <h2><?php echo $room['room_number'] ?></h2>
                    <form action="" method="post">
                        <p>Capacity: <?php echo $room['room_capacity'] ?></p>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <div id="add-user" class="popup">
            <div class="popup-content">
                <span class="close">&times;</span>
                <h2>Register</h2>
                <hr>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="room_number">Room Number:</label>
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
                            <input type="text" id="middlename" name="middlename" placeholder="Middle Name"  autocomplete="off">
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
                        <input type="tel" id="contact_number" name="contact_number" placeholder="Contact Number" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="tel" id="emergency_number" name="emergency_number" placeholder="Emergency Number" required autocomplete="off">
                    </div>
                    <button type="submit" name="submit-add">Submit</button>
                </form>
            </div>
        </div>

        <div id="update-user" class="popup">
            <div class="popup-content">
                <span class="close">&times;</span>
                <h2>Update</h2>
                <hr>
                <hr>
                <form action="" method="post">
                <div class="form-group">
                        <label for="room_number">Room Number:</label>
                        <select name="roomnumber" id="roomnumber" required>
                            <option value="">Select Room</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room['room_number']; ?>"><?php echo $room['room_number']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" name="submit">Submit</button>
                </form>
            </div>
        </div>
        <div id="delete-user" class="popup">
            <div class="popup-content">
                <span class="close">&times;</span>
                <h2>Delete</h2>
                <hr>
                <hr>
                <form action="" method="post">
                <div class="form-group">
                        <label for="room_number">Room Number:</label>
                        <select name="roomnumber" id="roomnumber" required>
                            <option value="">Select Room</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room['room_number']; ?>"><?php echo $room['room_number']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popups = document.querySelectorAll('.popup');
            const addUserPopup = document.getElementById("add-user");
            const updateUserPopup = document.getElementById("update-user");
            const deleteUserPopup = document.getElementById("delete-user");

            const addButtons = document.querySelectorAll('button[name="add"]');
            const updateButtons = document.querySelectorAll('button[name="update"]');
            const deleteButtons = document.querySelectorAll('button[name="delete"]');

            const closeButtons = document.querySelectorAll(".close");

            const openPopup = (popup) => {
                popup.style.display = "flex";
            };

            const closePopup = (popup) => {
                popup.style.display = "none";
            };

            addButtons.forEach(button => {
                button.addEventListener('click', () => {
                    openPopup(addUserPopup);
                });
            });

            updateButtons.forEach(button => {
                button.addEventListener('click', () => {
                    openPopup(updateUserPopup);
                });
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    openPopup(deleteUserPopup);
                });
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    closePopup(button.closest('.popup'));
                });
            });

            window.addEventListener('click', (event) => {
                if (event.target.classList.contains('popup')) {
                    closePopup(event.target);
                }
            });
        });
    </script>

</body>
</html>
