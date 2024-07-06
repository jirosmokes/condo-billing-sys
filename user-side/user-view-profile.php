<?php
include '../connection-db.php';

// Fetch user data
$user_id = 1; // Assuming you are fetching data for user with id 1
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
/*
// Handle profile picture upload
if (isset($_POST['upload'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profilePicture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
 // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
            $sql = "UPDATE users SET image='$target_file' WHERE id=$user_id";
            if ($conn->query($sql) === TRUE) {
                echo "<script>
                    document.getElementById('profile-pic').style.backgroundImage = 'url(" . $target_file . ")';
                    alert('The file " . htmlspecialchars(basename($_FILES["profilePicture"]["name"])) . " has been uploaded.');
                </script>";
            } else {
                echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    } else {
        echo "<script>alert('File is not an image.');</script>";
    }
}
*/
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
