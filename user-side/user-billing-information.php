<?php
session_start();
// Check if session variable for admin login is not set, redirect to login page
if (!isset($_SESSION['account_number'])) {
    header("Location: ../landing-page.php");
    exit();
}

require '../connection-db.php';

// Fetch distinct room numbers from the users table
$rooms_result = mysqli_query($conn, "SELECT room_number FROM users WHERE access_lvl = 'user'");
$rooms = [];
while ($row = mysqli_fetch_assoc($rooms_result)) {
    $rooms[] = $row['room_number']; // Store only the room_number in the array
}

$transactions = [];
$selected_room_number = mysqli_real_escape_string($conn, $_SESSION['room_number']);

// Fetch all transactions for the selected room into an array
$transactions_result = mysqli_query($conn, "SELECT * FROM transactions WHERE room_number = '$selected_room_number' ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($transactions_result)) {
    $transactions[$row['id']] = $row; // Store transaction details with ID as the key
}

// Initialize message variables
$message = '';
$error = '';

// Handle form submission
if (isset($_POST['submit'])) {
    $bill_id = mysqli_real_escape_string($conn, $_POST['bill_id']);
    $payer_name = mysqli_real_escape_string($conn, $_POST['payer_name']);
    $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
    $expiry = mysqli_real_escape_string($conn, $_POST['expiry']);
    $cvc = mysqli_real_escape_string($conn, $_POST['cvc']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $amount_paid = mysqli_real_escape_string($conn, $_POST['amount']);

    // Check if the room number matches the current session room number
    if (isset($transactions[$bill_id])) {
        $transaction = $transactions[$bill_id];
        if ($transaction['room_number'] != $selected_room_number) {
            $error = "You cannot pay bills for a different room.";
        } else {
            // Retrieve amount from array for validation
            $amount_in_db = $transaction['amount'];

            // Validate if the paid amount matches the amount in database
            if ($amount_paid == $amount_in_db) {
                // Update transaction status to "paid" and amount to 0
                $update_query = "UPDATE transactions SET status = 'paid', amount = 0 WHERE id = '$bill_id'";
                if (mysqli_query($conn, $update_query)) {
                    // Success message
                    $message = "Payment successful! Transaction ID: $bill_id";

                    // Update the local transaction array to reflect the status change
                    $transactions[$bill_id]['status'] = 'paid';
                    $transactions[$bill_id]['amount'] = 0;

                    // Optionally, proceed to the next bill in sequence
                    // This can be added if needed in the future
                    /*
                    $next_bill_id = getNextUnpaidBillId($transactions, $bill_id, $selected_room_number);
                    if ($next_bill_id !== null) {
                        $update_next_query = "UPDATE transactions SET status = 'unpaid' WHERE id = '$next_bill_id'";
                        if (mysqli_query($conn, $update_next_query)) {
                            $transactions[$next_bill_id]['status'] = 'unpaid';
                            $message .= "<br>Next bill (ID: $next_bill_id) is now ready for payment.";
                        } else {
                            $error = "Failed to update next bill status. Please try again.";
                        }
                    } else {
                        $message .= "<br>No more unpaid bills for this room.";
                    }
                    */
                } else {
                    $error = "Failed to update transaction status. Please try again.";
                }
            } else {
                $error = "Paid amount does not match the amount in database. Please verify and try again.";
            }
        }
    } else {
        $error = "Bill not found or does not belong to this room.";
    }
}

// Function to get the ID of the next unpaid bill from array
function getNextUnpaidBillId($transactions, $current_bill_id, $room_number) {
    $found_current = false;
    foreach ($transactions as $id => $transaction) {
        if ($transaction['room_number'] == $room_number) {
            if ($found_current && $transaction['status'] == 'unpaid') {
                return $id;
            }
            if ($id == $current_bill_id) {
                $found_current = true;
            }
        }
    }
    return null;
}

function getPreviousPaidBillId($transactions, $current_bill_id, $room_number) {
    $previous_bill_id = null;
    $found_current = false;
    foreach ($transactions as $id => $transaction) {
        if ($transaction['room_number'] == $room_number) {
            if ($id == $current_bill_id) {
                return $previous_bill_id;
            }
            if ($transaction['status'] == 'paid') {
                $previous_bill_id = $id;
            }
        }
    }
    return $previous_bill_id;
}


// Close connection (assuming your current code structure)
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Form</title>
<style>
    body {
        font-family: sans-serif;
        background-color: #202124;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        flex-direction: column;
    }
    .container {
        background-color: #2b2c30;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 800px;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #5f6368;
    }
    th {
        background-color: #38393d;
    }
    .pay-button, .cancel-button {
        background-color: #fb8c00;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-bottom: 10px;
    }
    .cancel-button {
        background-color: #5f6368;
    }
    .disclaimer {
        font-size: 12px;
        color: #9aa0a6;
        margin-top: 20px;
        text-align: center;
    }
    .bill-container {
        display: none;
        background-color: #2b2c30;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .bill-container h2 {
        margin-bottom: 10px;
    }
    .bill-container p {
        margin: 5px 0;
    }
    .card {
    background-color: #2b2c30;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    margin-top: 20px;
}

.card h2 {
    color: #fb8c00;
    margin-bottom: 20px;
}

.card .form-group {
    margin-bottom: 20px;
}

.card label {
    display: block;
    color: #fff;
    margin-bottom: 5px;
}

.card input[type="text"], .card textarea, .card select {
    width: 100%;
    padding: 10px;
    border: 1px solid #5f6368;
    border-radius: 5px;
    background-color: #38393d;
    color: #fff;
    box-sizing: border-box;
}

.card input[type="submit"], .card button {
    background-color: #fb8c00;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
}

.card input[type="submit"]:hover, .card button:hover {
    background-color: #f57c00;
}

.card .bill-container {
    display: none;
    background-color: #2b2c30;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.card .bill-container h2 {
    margin-bottom: 10px;
}

.card .bill-container p {
    margin: 5px 0;
}

.card .disclaimer {
    font-size: 12px;
    color: #9aa0a6;
    margin-top: 20px;
}

.card .card-number {
    display: flex;
    align-items: center;
}

.card .card-number .card-logo {
    margin-left: 10px;
}

.card .card-number .card-logo img {
    height: 24px;
}

.card .expiry-cvc-country {
    display: flex;
    justify-content: space-between;
}

.card .expiry-cvc-country .expiry, .card .expiry-cvc-country .cvc, .card .expiry-cvc-country .country {
    flex: 1;
    margin-right: 10px;
}

.card .expiry-cvc-country .country {
    margin-right: 0;
}

.message {
    color: green;
    margin-bottom: 10px;
}

.error {
    color: red;
    margin-bottom: 10px;
}

</style>
</head>
<body>
<?php if (!empty($message) || !empty($error)): ?>
    <div class="container">
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>


<?php if (!empty($transactions)): ?>
    <?php foreach ($transactions as $transaction): ?>
        <div class="container">
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
                    <tr>
                        <td><?php echo $transaction['id']; ?></td>
                        <td><?php echo $transaction['room_number']; ?></td>
                        <td><?php echo $transaction['amount']; ?></td>
                        <td><?php echo $transaction['description']; ?></td>
                        <td><?php echo $transaction['transaction_date']; ?></td>
                        <td><?php echo $transaction['status']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No transactions found for the selected room.</p>
<?php endif; ?>

<div class="card">
<?php if (!empty($message)): ?>
    <p class="message"><?php echo $message; ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="paymentForm">
    <div class="form-group">
        <label for="bill_id">Select Bill to Pay</label>
        <select id="bill_id" name="bill_id" required>
            <option value="">Select a Bill to Pay</option>
            <?php foreach ($transactions as $id => $transaction): ?>
                <option value="<?php echo $transaction['id']; ?>">
                    <?php echo $transaction['description']; ?> - ₱<?php echo $transaction['amount']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
    <div class="form-group">
        <label for="payer_name">Payer Name</label>
        <input type="text" id="payer_name" name="payer_name" placeholder="John Doe" required>
    </div>
    <div class="card-number">
        <label for="card_number">Card Number</label>
        <input type="text" id="card_number" name="card_number" placeholder="xxxx xxxx xxxx xxxx" required>
        <div class="card-logo">
            <img src="../images/visa.jpg" alt="Visa Logo">
        </div>
    </div>
    <div class="expiry-cvc-country">
        <div class="expiry">
            <label for="expiry">Expiry</label>
            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
        </div>
        <div class="cvc">
            <label for="cvc">CVC</label>
            <input type="text" id="cvc" name="cvc" placeholder="1234" required>
        </div>
        <div class="country">
            <label for="country">Country</label>
            <select id="country" name="country" required>
                <option value="">Select Country</option>
                <option value="USA">USA</option>
                <option value="Canada">Canada</option>
                <option value="UK">UK</option>
                <option value="PHILIPPINES">PHILIPPINES</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" id="amount" name="amount" required>
    </div>
    <button class="pay-button" type="submit" name="submit">Pay Now</button>
    <button type="button" class="cancel-button">Cancel</button>
</form>

<div id="billInfo" class="bill-container">
    <h2>Transaction Recorded</h2>
    <p>Thank you for your payment!</p>
    <p>Transaction ID: <span id="transactionId"></span></p>
    <p>Amount Paid: ₱<span id="amountPaid"></span></p>
    <p>Payer Name: <span id="payerName"></span></p>
</div>

<p class="disclaimer">
    By providing your card information, you allow DORMHUB to charge your card for future payments.
    If the price changes, we'll notify you beforehand.
    You can manage renewals anytime from <a href="../user-side/user-view-profile.php">My Account</a>.
</p>
</div>
</div>
</body>
</html>
