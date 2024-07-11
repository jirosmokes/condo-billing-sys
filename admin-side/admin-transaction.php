<?php 
require '../connection-db.php';

session_start(); 
if (empty($_SESSION['account_number'])) {
    header("Location: ../landing-page.php");
    exit();
}

$rooms_result = mysqli_query($conn, "SELECT room_number FROM users WHERE access_lvl = 'user'");
$rooms = [];
while ($row = mysqli_fetch_assoc($rooms_result)) {
    $rooms[] = $row['room_number']; 
}

$errors = [];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['submit'])) {
        $room_number = mysqli_real_escape_string($conn, $_POST['roomnumber']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $startdate = ($_POST['startdate'] != '') ? mysqli_real_escape_string($conn, $_POST['startdate']) : '1970-01-01';
        $duedate = ($_POST['duedate'] != '') ? mysqli_real_escape_string($conn, $_POST['duedate']) : '1970-01-01';
        $status = "pending";

        $stmt = mysqli_prepare($conn, "SELECT account_number FROM users WHERE room_number = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $room_number);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $account_number);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (empty($account_number)) {
            $errors[] = "Account number not found for the selected room.";
        }

        if (empty($room_number)) {
            $errors[] = "Room number is required.";
        }
        if (empty($amount) || !is_numeric($amount)) {
            $errors[] = "Amount must be a numeric value.";
        }
        if (empty($description)) {
            $errors[] = "Description is required.";
        }
        if (empty($status)) {
            $errors[] = "Status is required.";
        } elseif (!in_array($status, ['paid', 'pending'])) {
            $errors[] = "Invalid status value.";
        }

        if (empty($errors)) {
            $insert_query = "INSERT INTO transactions (account_number, room_number, amount, description, start_date, due_date, status) 
                            VALUES ('$account_number', '$room_number', '$amount', '$description', '$startdate', '$duedate', '$status')";
            
            if (mysqli_query($conn, $insert_query)) {
                $msg = "Transaction added.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    }
}

$transactions = [];
if (isset($_GET['room_number'])) {
    $selected_room_number = mysqli_real_escape_string($conn, $_GET['room_number']);
    $transactions_result = mysqli_query($conn, "SELECT * FROM transactions WHERE room_number = '$selected_room_number'");
    while ($row = mysqli_fetch_assoc($transactions_result)) {
        $transactions[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction</title>
    <link rel="stylesheet" href="../admin-style/admin-sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../images/dorm-hub-logo-official-2.png" type="image/png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(24, 25, 26);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            min-height: 100vh;
        }

        .container {
            background-color: #333;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            margin-left: 200px;

        }

        h2 {
            color: rgb(170, 254, 2);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #fff;
            margin-bottom: 5px;
        }

        input[type="text"],input[type="date"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #5f6368;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            box-sizing: border-box; 
        }

        input[type="submit"] {
            background-color: rgb(170, 254, 2);
            color: black;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #5f6368;
            text-align: center;
        }

        th {
            background-color: #444;
        }

        tbody tr:nth-child(even) {
            background-color: #333;
        }

        tbody tr:nth-child(odd) {
            background-color: #333;
        }

        p {
            color: #fff;
        }

        .message{
            width: 100%;
            background-color: #fff;
            background: rgb(170, 254, 2);
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 3px;
            color: black;
            text-align: center;
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
    <div class="container">
        <center><h2>Add Transaction</h2></center>
        <?php if (!empty($msg)) : ?>
            <p class="message"><?php echo $msg; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="roomnumber">Select Room:</label>
                <select name="roomnumber" id="roomnumber" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room; ?>"><?php echo $room; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" required placeholder="₱ 000.00"><br>
            </div>
            <div class="form-group">
                <label for="startdate">Starting Date:</label>
                <input type="date" id="startdate" name="startdate" required><br>
            </div>
            <div class="form-group">
                <label for="duedate">Due Date:</label>
                <input type="date" id="duedate" name="duedate" required><br>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea><br>
            </div>
            
            <input type="submit" name="submit" value="Add Transaction">
        </form>

        <center><h2 style="margin-top: 20px;">Transaction Details</h2></center>
        <form action="" method="GET">
            <div class="form-group">
                <label for="room_number" style="margin-bottom: 20px;">View Transactions for Room:</label>
                <select name="room_number" id="room_number" onchange="this.form.submit()">
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room; ?>" <?php if (isset($_GET['room_number']) && $_GET['room_number'] == $room) echo 'selected'; ?>><?php echo $room; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if (!empty($transactions)): ?>
            <table>
                <thead>
                    <tr> 
                        <th>ID</th>
                        <th>Room Number</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Transaction Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($transactions as $transaction) {
                        echo "<tr>
                            <td>{$transaction['id']}</td>
                            <td>{$transaction['room_number']}</td>
                            <td>{$transaction['amount']}</td>
                            <td>{$transaction['description']}</td>
                            <td>{$transaction['transaction_date']}</td>
                            <td>{$transaction['status']}</td>
                            </tr>";
                    }
                ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transactions found for the selected room.</p>
        <?php endif; ?>
    </div>
</body>
</html>
