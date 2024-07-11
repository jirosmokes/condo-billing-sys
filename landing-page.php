<?php
session_start();
require_once 'connection-db.php';

if (isset($_COOKIE['account-number'])) {
    $accountNumberCookie = $_COOKIE['account-number'];
} else {
    $accountNumberCookie = "";
}

if (isset($_COOKIE['password'])) {
    $passwordCookie = $_COOKIE['password'];
} else {
    $passwordCookie = "";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $accountNumber = $_POST['account-number'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE account_number = ?");
    $stmt->bind_param("s", $accountNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();
    
    if ($account) {
        if ($password == $account["account_password"]) {
            $_SESSION['account_number'] = $account['account_number'];
            $_SESSION['room_number'] = $account['room_number'];

            if (isset($_POST['rememberme'])) {
                setcookie('account-number', $accountNumber, time() + (86400 * 30), "/");
                setcookie('password', $password, time() + (86400 * 30), "/");
            } else {
                setcookie('account-number', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }
            if ($account['access_lvl'] == "user") {
                header("Location: user-side/user-dashboard.php");
                exit();
            } else {
                header("Location: admin-side/admin-dashboard.php");
                exit();
            }
        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "User not registered";
    }

    $stmt->close();
}

// Close the database connection (moved to the end of the script)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DormHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="landing-page.css?v=<?php echo time(); ?>">
    <link rel="icon" href="images/dorm-hub-logo-official-2.png" type="image/png">
</head>
<body>
    <header>
        <img src="images/dorm-hub-logo-official.png" alt="DormHub Logo" class="logo">
        <nav>
            <a href="about-us.php">About</a>
            <a href="rooms.php">Rooms</a>
        </nav>
        <div class="help-center">
            <a href="help-center.php" style="color:black; text-decoration:none;">Help Center</a>
        </div>
    </header>
    <main>
        <div class="content">
            <h1>Hi Tenant!</h1>
            <p class="welcome-text">Welcome to DormHub, your trusted platform for managing bills and payments. We're here to ensure your billing experience is smooth and hassle-free.</p>
            <div class="inside-container">
                <?php if(isset($error)): ?>
                    <div class="error">
                        <p><?php echo $error ?></p>
                    </div>
                <?php endif; ?>
                <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <input type="text" id="account-number" name="account-number" placeholder="Account Number" required value="<?php echo $accountNumberCookie ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Password" required value="<?php echo $passwordCookie ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit">Login</button>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="rememberme" id="rememberme">
                        <label for="rememberme">Remember Me</label>
                    </div>
                </form>
            </div>
        </div>
        <div class="image-container">
            <img src="images/background-1.png" alt="Dorm Room Illustration">
        </div>
    </main>
</body>
</html>
