<?php
// Start session if it's not already started
// Check if session variable for admin login is not set, redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    #header("Location: admin-landing.php");
    #exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to landing page after logout
    header("Location: ../landing-page.php");
    exit();
}

// Database connection (replace with your actual connection details)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dorm_hub_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of registered tenants
$sql = "SELECT COUNT(*) AS total_tenants FROM users";
$result = $conn->query($sql);

$totalTenants = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalTenants = $row['total_tenants'];
}
$sql_details = "SELECT account_number, last_name, room_number FROM users WHERE access_lvl != 'admin'";
$result_details = $conn->query($sql_details);

// Fetch billing logs with selected columns
$sql = "SELECT payer_name, card_number, expiry, amount, transaction_id, transaction_date FROM billing_information";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/dorm-hub-logo-official-2.png" type="image/png">
    <title>DH-Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../admin-style/admin-sidebar.css?v=<?php echo time(); ?>">
    <style>
        /* Sidebar styles */
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

        /* Content styles */
        #content {
            margin-left: 200px;
            padding: 20px;
            color: white;
            margin-right: 100px;
        }

        body {
            margin: 0;
            font-family: "Open Sans", sans-serif;
            background-color: #1e1e1e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #2c2c2c;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <header><img src="../images/dorm-hub-logo-official.png" alt="" height="30px"></header>

        <a href="#" class="active">
            <i class="fas fa-qrcode"></i>
            <span>Dashboard</span>
        </a>

        <a href="../admin-side/admin-room-selection.php">
            <i class="fas fa-user-alt"></i>
            <span>View Tenants</span>
        </a>
        <form method="post" action="../logout.php">
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div id="content">
        <!-- Total Tenants Section -->
        <section id="totalTenants" style="display: inline-block; width: 100%; padding: 15px; border-radius: 10px; background-color: #333; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);">
            <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Total Registered Tenants</h2>
            <p style="color: #ccc; font-size: 14px; line-height: 1.4; margin: 0;"><?php echo "Total: " . $totalTenants; ?></p>

                <!-- Tenant Details Table -->
            <section id="tenantDetails" style="margin-top: 20px;">
                <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Tenant Details</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Account Number</th>
                            <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Last Name</th>
                            <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Room Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_details->num_rows > 0) {
                            while ($row_details = $result_details->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td style='color: #ccc; padding: 8px;'>{$row_details['account_number']}</td>";
                                echo "<td style='color: #ccc; padding: 8px;'>{$row_details['last_name']}</td>";
                                echo "<td style='color: #ccc; padding: 8px;'>{$row_details['room_number']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' style='color: #ccc; padding: 8px; text-align: center;'>No tenant details found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </section>

        <!-- Total Revenue Section -->
        <section id="totalRevenue" style="display: inline-block; width: 100%;padding: 15px; border-radius: 10px; background-color: #333; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); margin-top: 20px;">
            <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Total Revenue</h2>
            <p style="color: #ccc; font-size: 14px; line-height: 1.4; margin: 0;">Total Revenue: Pesos</p> <!-- Display total revenue --> 

            <!-- Canvas for Chart.js -->
            <canvas id="revenueChart" style="margin-top: 10px; max-height: 200px;"></canvas>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Example data for Chart.js
                var ctx = document.getElementById('revenueChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                        datasets: [{
                            label: 'Revenue',
                            data: [500, 300, 600, 300, 900, 1500], // Example static revenue data
                            borderColor: '#4CAF50', // Green color for the line
                            backgroundColor: 'rgba(76, 175, 80, 0.2)', // Green color with transparency for the fill
                            borderWidth: 2,
                            pointRadius: 5,
                            pointBackgroundColor: '#4CAF50', // Green color for points
                            pointBorderColor: '#4CAF50',
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#4CAF50',
                            pointHoverBorderColor: '#4CAF50'
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            </script>
        </section>

        <!-- Billing Logs -->
        <section id="billingLogs" style="display: inline-block; width: 100%; padding: 15px; border-radius: 10px; background-color: #333; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); margin-top: 20px;">
            <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Billing Logs</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Payer Name</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Card Number</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Expiry</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Amount</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Transaction ID</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$row['payer_name']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$row['card_number']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$row['expiry']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$row['amount']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$row['transaction_id']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$row['transaction_date']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='color: #ccc; padding: 8px; text-align: center;'>No billing logs found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
