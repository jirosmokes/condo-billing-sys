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
$transactions_result = mysqli_query($conn, "SELECT * FROM transactions WHERE room_number = '$selected_room_number'");
while ($row = mysqli_fetch_assoc($transactions_result)) {
    $transactions[] = $row;
}

// Close connection
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
</style>
</head>
<body>
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

<?php /* 
<div class="container">
    <h2>Card Details</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="paymentForm">
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
*/ ?>

<script>
    const cancelButton = document.querySelector('.cancel-button');
    cancelButton.addEventListener('click', () => {
        alert('Payment cancelled!');
    });
</script>
</body>
</html>
