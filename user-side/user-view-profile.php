<?php
session_start();
require '../connection-db.php';


if (!isset($_SESSION['account_number'])) {
    header('Location: ../user-side/user-dashboard.php');
    exit;
}


$account_number = $_SESSION['account_number'];
$room_number = $_SESSION['room_number'];
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


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profilePicture'])) {
    $uploadMessage = upload_image($account_number, $conn);
    echo "<script>alert('$uploadMessage');</script>";
    header("Refresh:0");
    exit; 
}


$billing_logs_sql = "SELECT due_date, amount, status FROM transactions WHERE room_number = ? ORDER BY due_date ASC";
$stmt = $conn->prepare($billing_logs_sql);
$stmt->bind_param("s", $room_number);
$stmt->execute();
$billing_logs_result = $stmt->get_result();

$billing_logs = [];
if ($billing_logs_result->num_rows > 0) {
    while ($row = $billing_logs_result->fetch_assoc()) {
        $billing_logs[] = $row;
    }
} else {
    $billing_logs[] = ['NULL', 'NULL', 'NULL'];
}

$stmt->close();

// Fetch payment transaction details
$payment_transaction_sql = "SELECT payer_name, transaction_id, transaction_date FROM billing_information WHERE room_number = ? ORDER BY transaction_date ASC";
$stmt = $conn->prepare($payment_transaction_sql);
$stmt->bind_param("s", $room_number);
$stmt->execute();
$payment_transaction_result = $stmt->get_result();

$payment_transactions = [];
if ($payment_transaction_result->num_rows > 0) {
    while ($row = $payment_transaction_result->fetch_assoc()) {
        $payment_transactions[] = $row;
    }
} else {
    $payment_transactions[] = ['NULL', 'NULL', 'NULL'];
}

$stmt->close();
$conn->close();

function upload_image($account_number, $conn) {
    if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] == 0) {
        $profile_picture = $_FILES["profilePicture"];
        $target_dir = "../images/";
        $target_file = $target_dir . basename($profile_picture["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if file is an actual image
        $check = getimagesize($profile_picture["tmp_name"]);
        if ($check === false) {
            return "File is not an image.";
        }

        // Check file size
        if ($profile_picture["size"] > 500000) {
            return "Sorry, your file is too large.";
        }

       
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedFormats)) {
            return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        
        if (file_exists($target_file)) {
            return "Sorry, file already exists.";
        }

        // Attempt to move the uploaded file
        if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
            $imagePath = "images/" . basename($profile_picture["name"]);
            $sql = "UPDATE users SET profile_picture = ? WHERE account_number = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $imagePath, $account_number);
            if ($stmt->execute()) {
                $stmt->close();
                return "The file " . htmlspecialchars(basename($profile_picture["name"])) . " has been uploaded.";
            } else {
                $stmt->close();
                return "Sorry, there was an error updating your profile.";
            }
        } else {
            return "Sorry, there was an error uploading your file.";
        }
    } else {
        return "No file was uploaded or there was an upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../user-style/user-view-profile.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="stylesheet" href="../admin-style/admin-sidebar.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Profile View</title>
</head>
<body>
<div class="sidebar">
        <header><img src="../images/dorm-hub-logo-official.png" alt="" height="30px"></header>

       

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
    <div class="card">
        <div class="header">
            <div class="profile-pic" id="profile-pic" style="background-image: url('../<?php echo isset($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : ''; ?>');">
                <form id="upload-form" action="" method="post" enctype="multipart/form-data">
                    <label for="file-upload" class="upload-label">Upload</label>
                    <input type="file" id="file-upload" name="profilePicture" accept="image/*" onchange="document.getElementById('upload-form').submit();">
                </form>
            </div>
            <div class="info">
                <h2><?php echo htmlspecialchars(strtoupper($user['first_name'] . ' ' . $user['last_name'])); ?></h2>
                <div class="left">
                    <div>Email: <?php echo htmlspecialchars($user['account_number']); ?></div>
                    <div>Contact No.: <?php echo htmlspecialchars($user['contact_number']); ?></div>
                
                    <div>School: <?php echo htmlspecialchars($user['school']); ?></div>
                    <div>Room No.: <?php echo htmlspecialchars($user['room_number']); ?></div>
                    <div>Emergency No.: <?php echo htmlspecialchars($user['emergency_number']); ?></div>
                </div>
            </div>
        </div>
        <h3>Billing Logs:</h3>
        <div class="table-container scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $counter = 1; // Initialize counter
                foreach ($billing_logs as $log) {
                    echo '<tr>';
                    echo '<td style="width: 30px; text-align: center; padding: 5px;">' . $counter . '</td>'; // Display the counter with styling
                    foreach ($log as $item) {
                        echo '<td>' . htmlspecialchars($item) . '</td>';
                    }
                    echo '</tr>';
                    $counter++; // Increment counter
                }
            ?>
                </tbody>
            </table>
        </div>
        <h3>Payment Transaction:</h3>
        <div class="table-container scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Payer Name</th>
                        <th>Transaction ID</th>
                        <th>Date Paid</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $counter = 1; // Initialize counter
                foreach ($payment_transactions as $transaction) {
                    echo '<tr>';
                    echo '<td style="width: 30px; text-align: center; padding: 5px;">' . $counter . '</td>'; // Display the counter with styling
                    foreach ($transaction as $item) {
                        echo '<td>' . htmlspecialchars($item) . '</td>';
                    }
                    echo '</tr>';
                    $counter++; // Increment counter
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

