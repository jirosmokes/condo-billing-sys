<!DOCTYPE html>
<html>
<head>
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
        .sidebar {
	position: fixed;
	top: 0;
	left: 0;
	width: 250px;
	height: 100%;
	background: linear-gradient(145deg, #1e1e1e, #2c2c2c);
	box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
	transition: all 0.3s;
	overflow: hidden;
	display: flex;
	flex-direction: column;
}

.sidebar header {
	display: flex;
	align-items: center;
	justify-content: center;
	height: 70px;
	background: #222;
	box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

.sidebar a {
	display: flex;
	align-items: center;
	padding: 15px 30px;
	color: white;
	text-decoration: none;
	transition: background-color 0.3s, transform 0.3s;
}

.sidebar a:hover {
	background-color: rgba(255, 255, 255, 0.1);
	transform: translateX(5px);
}

.sidebar a.active {
	background-color: #575757;
}

.sidebar a i {
	margin-right: 10px;
}

.sidebar form {
	margin-top: auto; /* Push the form to the bottom */
}

.sidebar form button {
	background: none;
	border: none;
	color: white;
	cursor: pointer;
	display: block;
	font-family: "Open Sans", sans-serif;
	font-size: 16px;
	line-height: 65px;
	padding-left: 30px;
	text-align: left;
	transition: all 0.3s ease;
	width: 100%;
	box-sizing: border-box;
}

.sidebar form button:hover {
	background-color: rgba(255, 255, 255, 0.1);
}

.sidebar form button i {
	margin-right: 10px;
}

.sidebar form button span {
	letter-spacing: 1px;
	text-transform: uppercase;
}
    </style>
</head>
<body>
<div class="sidebar">
        <header><img src="../images/dorm-hub-logo-official.png" alt="" height="30px"></header>

        <a href="../user-side/user-billing-information.php" class="active" onclick="showContent('dashboard')">
            <i class="fas fa-qrcode"></i>
            <span>Dashboard</span>
        </a>

        <a href="../admin-side/admin-room-selection.php" onclick="showContent('view-tenants')">
            <i class="fas fa-user-alt"></i>
            <span>View Tenants</span>
        </a>

        <a href="#" onclick="showContent('view-revenue')">
            <i class="fa-solid fa-money-bills"></i>
            <span>Revenue</span>
        </a>

        <a href="#">
            <i class="far fa-question-circle"></i>
            <span>About</span>
        </a>

        <form method="post" action="">
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
    <div class="container">
        <h2>Card Details</h2>
        <form id="paymentForm">
            <div class="card-number">
                <label for="cardNumber">Card number</label>
                <input type="text" id="cardNumber" name="cardNumber" placeholder="xxxx xxxx xxxx xxxx">
                <div class="card-logo">
                    <img src="../images/visa.jpg" alt="Visa Logo">
                </div>
            </div>
            <div class="expiry-cvc-country">
                <div class="expiry">
                    <label for="expiry">Expiry</label>
                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY">
                </div>
                <div class="cvc">
                    <label for="cvc">CVC</label>
                    <input type="text" id="cvc" name="cvc" placeholder="1234">
                </div>
                <div class="country">
                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="Philippines">Philippines</option>
                    </select>
                </div>
            </div>
            <div class="amount">
                <label for="amount">Amount to Pay:</label>
                <input type="number" id="amount" name="amount" placeholder="₱">
            </div>
            <button class="pay-button" type="submit">Pay</button>
            <button type="button" class="cancel-button">Cancel</button> 
            <p class="disclaimer">
                By providing your card information, you allow DORMHUB to charge your card for future payments. If the price changes, we'll notify you beforehand.
                You can manage renewals anytime from <a href="#">My Account</a>.
            </p>
        </form>

        <div id="billInfo" class="bill-container">
            <h2>Transaction Recorded</h2>
            <p>Thank you for your payment!</p>
            <p>Transaction ID: <span id="transactionId"></span></p> 
            <p>Amount Paid: ₱<span id="amountPaid"></span></p>
        </div>
    </div>

    <script>
        const paymentForm = document.getElementById('paymentForm');
        const billInfo = document.getElementById('billInfo');
        const transactionIdSpan = document.getElementById('transactionId');
        const amountPaidSpan = document.getElementById('amountPaid');

        paymentForm.addEventListener('submit', (event) => {
            event.preventDefault(); 
            
            const amount = document.getElementById('amount').value;
            const transactionId = Math.random().toString(36).substr(2, 9).toUpperCase();

            transactionIdSpan.textContent = transactionId;
            amountPaidSpan.textContent = amount;
            billInfo.style.display = 'block';
        });

        const cancelButton = document.querySelector('.cancel-button');
        cancelButton.addEventListener('click', () => {
            alert('Payment cancelled!');
        });
    </script>
</body>
</html>