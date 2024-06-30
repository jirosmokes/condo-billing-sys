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

$password_confirm = false;
$error = "";
if(isset($_POST['submit'])) {
    $accountNumber = $_POST['account-number'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user_accounts WHERE account_number = ?");
    $stmt->bind_param("s", $accountNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();
    
    if ($account) {
        // Verify the password
        if ($password == $account["password"]) {
            $_SESSION['name'] = $account['name'];

            if (isset($_POST['rememberme'])) {
                setcookie('account-number', $accountNumber, time() + (86400 * 30), "/");
                setcookie('password', $password, time() + (86400 * 30), "/");
            } else {
                setcookie('account-number', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }
            header("Location: user-side/user-dashboard.php");
            exit();
        } else {
            $error = "Wrong password";
            $password_confirm = true;
        }
    } else {
        $error = "User not registered";
        $password_confirm = true;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DormHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="landing-page.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <img src="images/dorm-hub-logo-official.png" alt="DormHub Logo" class="logo">
        <nav>
            <a href="#">About</a>
            <a href="admin-side/admin-landing.php">Admin</a>
        </nav>
        <button class="help-center">Help Center</button>
    </header>
    <main>
        <div class="content">
            <h1>Hi Tenant!</h1>
            <p class="welcome-text">Welcome to DormHub, your trusted platform for managing bills and payments. We're here to ensure your billing experience is smooth and hassle-free.</p>
            <div class="inside-container">
                <?php if($password_confirm): ?>
                    <div class="error">
                        <p><?php echo $error ?></p>
                    </div>
                <?php endif; ?>
                <form class="login-form" action="" method="POST">
                    <div class="form-group">
                        <input type="text" id="account-number" name="account-number" placeholder="Account Number" required value="<?php echo $accountNumberCookie ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Password" required value="<?php echo $passwordCookie ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit">Login</button>
                    </div>
                    <div class="forgot">
                        <a href="#">Forgot Password?</a>
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
