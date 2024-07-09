<?php
session_start();

if (empty($_SESSION['account_number'])) {
    header("Location: ../landing-page.php");
    exit();
}

require '../connection-db.php';

$rooms_result = mysqli_query($conn, "SELECT room_number FROM users WHERE access_lvl = 'user'");
$rooms = [];
while ($row = mysqli_fetch_assoc($rooms_result)) {
    $rooms[] = $row['room_number']; 
}

$transactions = [];
$selected_account_number = mysqli_real_escape_string($conn, $_SESSION['account_number']);

$transactions_result = mysqli_query($conn, "SELECT * FROM transactions WHERE account_number = '$selected_account_number'");
while ($row = mysqli_fetch_assoc($transactions_result)) {
    $transactions[$row['id']] = $row; 
}

$message = '';
$error = '';

// Handle form submission
if (isset($_POST['submit'])) {
    $bill_id = mysqli_real_escape_string($conn, $_POST['bill_id']);
    $payer_name = mysqli_real_escape_string($conn, $_POST['payer_name']);
    $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $amount_paid = mysqli_real_escape_string($conn, $_POST['amount']);

    // Check if the account number matches the current session account number
    if (isset($transactions[$bill_id])) {
        $transaction = $transactions[$bill_id];
        if ($transaction['account_number'] != $selected_account_number) {
            $error = "You cannot pay bills for a different account.";
        } else {

            $amount_in_db = $transaction['amount'];


            if ($amount_paid == $amount_in_db) {

                $update_query = "UPDATE transactions SET status = 'PAID' WHERE id = '$bill_id'";
                if (mysqli_query($conn, $update_query)) {

                    $message = "Payment successful! Transaction ID: $bill_id";


                    $transactions[$bill_id]['status'] = 'PAID';


                    $next_bill_id = getNextUnpaidBillId($transactions, $bill_id, $selected_account_number);
                    if ($next_bill_id !== null) {
                        $update_next_query = "UPDATE transactions SET status = 'PENDING' WHERE id = '$next_bill_id'";
                        if (mysqli_query($conn, $update_next_query)) {
                            $transactions[$next_bill_id]['status'] = 'PENDING';
                            $message .= "<br>Next bill (ID: $next_bill_id) is now ready for payment.";
                        } else {
                            $error = "Failed to update next bill status. Please try again.";
                        }
                    } else {
                        $message .= "<br>No more unpaid bills for this account.";
                    }


                    $room_number = $_SESSION['room_number'];
                    $sql = "INSERT INTO billing_information (account_number, room_number, card_number, country, amount, transaction_date, payer_name)
                            VALUES ('$selected_account_number', '$room_number', '$card_number', '$country', '$amount_paid', current_timestamp(), '$payer_name')";

                    if (!mysqli_query($conn, $sql)) {
                        $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }
                } else {
                    $error = "Failed to update transaction status. Please try again.";
                }
            } else {
                $error = "Paid amount does not match the amount in database. Please verify and try again.";
            }
        }
    } else {
        $error = "Bill not found or does not belong to this account.";
    }


    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}


function getNextUnpaidBillId($transactions, $current_bill_id, $account_number)
{
    $found_current = false;
    foreach ($transactions as $id => $transaction) {
        if ($transaction['account_number'] == $account_number) {
            if ($found_current && $transaction['status'] == 'PENDING') {
                return $id;
            }
            if ($id == $current_bill_id) {
                $found_current = true;
            }
        }
    }
    return null;
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link rel="stylesheet" href="../admin-style/admin-sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../images/dorm-hub-logo-official-2.png" type="image/png">
    <style>
        body {
            font-family: sans-serif;
            background-color: rgb(24, 25, 26);
            color: #fff;
            display: flex;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            flex-direction: column;
            justify-content: center;
        }

        .container {
            background-color: #333;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            margin-bottom: 20px;
            text-align: center;
            margin-top: 50px;
        }

        .container h2 {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #5f6368;
            text-align: center;
        }

        th {
            background-color: #444;
        }

        .pay-button {
            background-color: rgb(170, 254, 2);
            color: black;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
        }

        .cancel-button {
            background-color: grey;
        }

        .disclaimer {
            font-size: 12px;
            color: #9aa0a6;
            margin-top: 20px;
            text-align: center;
        }

        .bill-container {
            display: none;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .bill-container h2 {
            text-align: center;
        }

        .bill-container p {
            margin: 5px 0;
        }

        .card {
            background-color: #333;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
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

        .card input[type="text"],
        .card textarea,
        .card select {
            width: 100%;
            padding: 10px;
            border: 1px solid #5f6368;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            box-sizing: border-box;
        }

        .card input[type="submit"],
        .card button {
            color: black;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .card .bill-container {
            display: none;
            background-color: #2b2c30;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
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

        .card .expiry-cvc-country .expiry,
        .card .expiry-cvc-country .cvc,
        .card .expiry-cvc-country .country {
            flex: 1;
            margin-right: 10px;
        }

        .card .expiry-cvc-country .country {
            margin-right: 0;
        }

        .message {
            width: 100%;
        }

        .message {
            width: 100%;
            background-color: #fff;
            background: rgb(170, 254, 2);
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 3px;
            color: black;
        }

        .error {
            width: 100%;
            background-color: #fff;
            background: red;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 3px;
            color: black;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <header><img src="../images/dorm-hub-logo-official.png" alt="" height="30px"></header>

        <!--a href="#" class="active">
            <i class="fas fa-qrcode"></i>
            <span>Dashboard</span>
        </a-->

        <a href="../user-side/user-view-profile.php " onclick="showContent('profile')">
            <i class="fas fa-user-alt"></i>
            <span>Profile</span>
        </a>

        <a href="../user-side/user-billing-information.php" onclick="showContent('bills')">
            <i class="fa-solid fa-money-bills"></i>
            <span>Bills</span>
        </a>
        <form method="post" action="../logout.php">
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    <div class="container">
        <h2>Transaction History</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Transaction Date</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($transactions as $transaction) : ?>
                <tr>
                    <td><?php echo $transaction['id']; ?></td>
                    <td><?php echo $transaction['amount']; ?></td>
                    <td><?php echo $transaction['description']; ?></td>
                    <td><?php echo $transaction['transaction_date']; ?></td>
                    <td><?php echo $transaction['start_date']; ?></td>
                    <td><?php echo $transaction['due_date']; ?></td>
                    <td>
                        <?php if ($transaction['status'] == 'PENDING') : ?>
                            <button class="pay-button" onclick="showPaymentForm(<?php echo $transaction['id']; ?>)">Pay</button>
                        <?php else : ?>
                            <p>PAID</p>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="bill-container" id="billContainer">
        <h2>Payment Form</h2>
        <div class="card">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="paymentForm" style="margin-button: 5px;">
                <div class="form-group">
                    <label for="bill_id">Select Bill to Pay</label>
                    <select id="bill_id" name="bill_id" required>
                        <option value="">Select a Bill to Pay</option>
                        <?php foreach ($transactions as $id => $transaction) : ?>
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
                    <div class="card-number" style="margin-bottom:5px;">
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
                                <option value="PHILIPPINES">Philippines</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="text" id="amount" name="amount" required>
                    </div>
                    <button class="pay-button" type="submit" name="submit">Pay Now</button>
                    <button class="cancel-button" type="button" onclick="hidePaymentForm()">Cancel</button>
            </form>
            <p class="disclaimer">
                By providing your card information, you allow DORMHUB to charge your card for future payments.
                If the price changes, we'll notify you beforehand.
                You can manage renewals anytime from <a href="../user-side/user-view-profile.php">My Account</a>.
            </p>
        </div>
    </div>

    <script>
        function showPaymentForm(billId) {
            document.getElementById('bill_id').value = billId;
            document.getElementById('billContainer').style.display = 'block';
        }


        function hidePaymentForm() {
            document.getElementById('billContainer').style.display = 'none';
        }
    </script>
</body>

</html>