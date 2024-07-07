<?php
session_start();
require '../connection-db.php';

// Check if user is logged in
if (!isset($_SESSION['account_number'])) {
    header('Location: ../user-side/user-dashboard.php');
    exit;
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

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profilePicture'])) {
    $uploadMessage = upload_image($account_number, $conn);
    echo "<script>alert('$uploadMessage');</script>";
    header("Refresh:0"); // Refresh the page to show the updated image
    exit; // Ensure script stops after refresh
}

$conn->close();

function upload_image($account_number, $conn) {
    if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] == 0) {
        $image = $_FILES["profilePicture"];
        $target_dir = "../images/";
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if file is an actual image
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            return "File is not an image.";
        }

        // Check file size
        if ($image["size"] > 500000) {
            return "Sorry, your file is too large.";
        }

        // Allow certain file formats
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedFormats)) {
            return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            return "Sorry, file already exists.";
        }

        // Attempt to move the uploaded file
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            $imagePath = "images/" . basename($image["name"]);
            $sql = "UPDATE users SET image = ? WHERE account_number = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $imagePath, $account_number);
            if ($stmt->execute()) {
                $stmt->close();
                return "The file " . htmlspecialchars(basename($image["name"])) . " has been uploaded.";
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
    <title>Contact Card</title>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="profile-pic" id="profile-pic" style="background-image: url('../<?php echo isset($user['image']) ? htmlspecialchars($user['image']) : ''; ?>');">
                <form id="upload-form" action="" method="post" enctype="multipart/form-data">
                    <label for="file-upload" class="upload-label">Upload</label>
                    <input type="file" id="file-upload" name="profilePicture" accept="image/*" onchange="document.getElementById('upload-form').submit();">
                </form>
            </div>
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
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date Paid</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Assuming $billing_logs fetched from database
                        $billing_logs = [
                            ['N/A', 'N/A', 'N/A', 'N/A'],
                            ['N/A', 'N/A', 'N/A', 'N/A'],
                            ['N/A', 'N/A', 'N/A', 'N/A']
                        ];

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
