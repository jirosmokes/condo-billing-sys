<?php
session_start();
require_once '../connection-db.php';

// Check if user is logged in
if (!isset($_SESSION['account_number'])) {
/*    header('Location: ../user-side/user-dashboard.php');
    exit;
    */
}

// Fetch user data based on account_number
$account_number = $_SESSION['account_number'];
$sql = "SELECT * FROM users WHERE account_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $account_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../user-style/user-view-profile.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <title>Contact Card</title>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="profile-pic" id="profile-pic" style="background-image: url('<?php echo isset($user['image']) ? $user['image'] : ''; ?>');">
                <label for="file-upload" class="upload-label">Upload</label>
                <input type="file" id="file-upload" name="profilePicture" accept="image/*" onchange="document.getElementById('upload-form').submit();">
            </div>
            <form id="upload-form" action="" method="post" enctype="multipart/form-data" style="display:none;">
                <input type="submit" name="upload" value="Upload">
            </form>
            <div class="info">
                <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                <div class="left">
                    <div>Email: <?php echo htmlspecialchars($user['account_number']); ?></div>
                    <div>Contact No.: <?php echo htmlspecialchars($user['contact_number']); ?></div>
                </div>
                <div class="right">
                    <div>School: <?php echo htmlspecialchars($user['school']); ?></div>
                    <div>Room No.: <?php echo htmlspecialchars($user['room_number']); ?></div>
                </div>
            </div>
        </div>
        <div class="table-container">
            <h3>Billing Logs:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Due Date</th>
                        <th>Price</th>
                        <th>State</th>
                        <th>Date Paid</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Sample data array
                        $billing_logs = [
                            ['2024-07-01', '$100', 'Paid', '2024-07-02'],
                            ['2024-07-15', '$150', 'Unpaid', ''],
                            ['2024-08-01', '$200', 'Paid', '2024-08-02'],
                        ];

                        // Display data in table rows
                        foreach ($billing_logs as $log) {
                            echo '<tr>';
                            foreach ($log as $item) {
                                echo '<td>' . htmlspecialchars($item) . '</td>';
                            }
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
