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
    }
    .container {
        background-color: #2b2c30;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #5f6368;
        border-radius: 5px;
        background-color: #38393d;
        color: #fff;
    }
    .card-number {
        display: flex;
        align-items: center;
    }
    .card-logo {
        margin-left: 10px;
    }
    .card-logo img {
        height: 24px;
    }
    .expiry-cvc-country {
        display: flex;
        justify-content: space-between;
    }
    .expiry, .cvc, .country {
        flex: 1;
        margin-right: 10px;
    }
    .country {
        margin-right: 0;
    }
    .pay-button {
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
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-bottom: 10px;
    }
    .disclaimer {
        font-size: 12px;
        color: #9aa0a6;
        margin-top: 20px;
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

<div class="container">
    <h2>Card Details</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="paymentForm">
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
    </div>

    <p class="disclaimer">
        By providing your card information, you allow DORMHUB to charge your card for future payments.
        If the price changes, we'll notify you beforehand.
        You can manage renewals anytime from <a href="../user-side/user-view-profile.php">My Account</a>.
    </p>

    <?php
    require '../connection-db.php';

        // Validate and sanitize input data
        $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
        $expiry = mysqli_real_escape_string($conn, $_POST['expiry']);
        $cvc = mysqli_real_escape_string($conn, $_POST['cvc']);
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $transaction_id = uniqid(); // Generate a unique transaction ID

        // SQL insert statement
        $sql = "INSERT INTO billing_information (card_number, expiry, cvc, country, amount, transaction_id)
                VALUES ('$card_number', '$expiry', '$cvc', '$country', '$amount', '$transaction_id')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>
                    document.getElementById("transactionId").textContent = "' . $transaction_id . '";
                    document.getElementById("amountPaid").textContent = "' . $amount . '";
                    document.getElementById("billInfo").style.display = "block";
                  </script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close connection
        $conn->close();
    }
    ?>
</div>

<script>
    const cancelButton = document.querySelector('.cancel-button');
    cancelButton.addEventListener('click', () => {
        alert('Payment cancelled!');
    });
</script>

</body>
</html>
