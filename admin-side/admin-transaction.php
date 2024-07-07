<?php 
require '../connection-db.php';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
    <h2>Add Transaction</h2>
        <form action="add-transaction.php" method="POST">
            <div class="form-group">
                <select name="roomnumber" id="roomnumber" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['room_number']; ?>"><?php echo $room['room_number']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <label for="amount">Amount:</label>
            <input type="text" id="amount" name="amount" required><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br>
            <input type="submit" value="Add Transaction">
        </form>
    </div>
</body>
</html>