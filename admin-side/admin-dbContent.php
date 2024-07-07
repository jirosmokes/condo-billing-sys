<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dorm_hub_db";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) AS total_tenants FROM users";
$result = $conn->query($sql);

$totalTenants = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalTenants = $row['total_tenants'];
}


$sql_details = "SELECT account_number, last_name, room_number FROM users WHERE access_lvl != 'admin'";
$result_details = $conn->query($sql_details);


$sqlRevenue = "
    SELECT SUM(amount) AS total_amount, MONTH(start_date) AS transaction_month
    FROM transactions 
    WHERE status = 'paid'
    GROUP BY MONTH(start_date)
    ORDER BY MONTH(start_date)
";

$resultRevenue = $conn->query($sqlRevenue);


$sqlPaidTransactions = "
    SELECT room_number, amount, description, status, transaction_date
    FROM transactions 
    WHERE status = 'paid'
    ORDER BY transaction_date DESC
";

$resultPaidTransactions = $conn->query($sqlPaidTransactions);
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
        #content {
            margin-left: 250px;
            padding: 20px;
            color: white;
            margin-right: 100px;
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
            <i class="fa-solid fa-users-viewfinder"></i>
            <span>View Tenants</span>
        </a>

        <a href="../admin-side/admin-transaction.php">
            <i class="fas fa-money-bill-transfer"></i>
            <span>Create Bills</span>
        </a>

        <form method="post" action="../logout.php">
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div id="content">
       
        <section id="totalTenants" style="display: inline-block; width: 100%; padding: 15px; border-radius: 10px; background-color: #333; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);">
            <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Total Registered Tenants</h2>
            <p style="color: #ccc; font-size: 14px; line-height: 1.4; margin: 0;"><?php echo "Total: " . $totalTenants; ?></p>

           
            <section id="tenantDetails" style="margin-top: 20px;">
                <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Tenant Details</h2>
                <table>
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

      
        <section id="totalRevenue" style="display: inline-block; width: 100%;padding: 15px; border-radius: 10px; background-color: #333; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); margin-top: 20px;">
            <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Total Revenue (Chart)</h2>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </section>


        <section id="paidTransactions" style="display: inline-block; width: 100%; padding: 15px; border-radius: 10px; background-color: #333; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); margin-top: 20px;">
            <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Paid Transactions</h2>
            <table>
                <thead>
                    <tr>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Room Number</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Amount</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Description</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Status</th>
                        <th style="color: white; border-bottom: 1px solid #444; padding: 8px;">Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultPaidTransactions->num_rows > 0) {
                        while ($rowPaidTransactions = $resultPaidTransactions->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$rowPaidTransactions['room_number']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$rowPaidTransactions['amount']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$rowPaidTransactions['description']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$rowPaidTransactions['status']}</td>";
                            echo "<td style='color: #ccc; padding: 8px;'>{$rowPaidTransactions['transaction_date']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='color: #ccc; padding: 8px; text-align: center;'>No paid transactions found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

var ctx = document.getElementById('revenueChart').getContext('2d');
var revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php
                    while ($rowRevenue = $resultRevenue->fetch_assoc()) {
                        echo '"' . date('M', mktime(0, 0, 0, $rowRevenue['transaction_month'], 1)) . '", ';
                    }
                    ?>],
        datasets: [{
            label: 'Total Revenue',
            data: [<?php
                    $resultRevenue->data_seek(0); 
                    while ($rowRevenue = $resultRevenue->fetch_assoc()) {
                        echo $rowRevenue['total_amount'] . ', ';
                    }
                    ?>],
            borderColor: 'rgb(170, 254, 2)', 
            backgroundColor: 'rgba(170, 241, 28, 0.3)', 
            borderWidth: 2,
            pointRadius: 5,
            pointBackgroundColor: 'rgb(170, 254, 2)', 
            pointBorderColor: 'rgb(170, 254, 2)',
            pointHoverRadius: 7,
            pointHoverBackgroundColor: 'rgba(75, 192, 192, 1)',
            pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
            fill: true,
            tension: 0.4 
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</body>
</html>

<?php

$conn->close();
?>
