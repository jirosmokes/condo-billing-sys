<?php
session_start();

// Check if user is already logged in, redirect to admin dashboard if true
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin-dashboard.php");
    exit();
}

// Initialize variables
$username = $password = "";
$error_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define admin credentials
    $admin_username = "admin";
    $admin_password = "admin123";

    // Retrieve user input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verify credentials
    if ($username === $admin_username && $password === $admin_password) {
        // Set session variables
        $_SESSION['admin_logged_in'] = true;

        // Redirect to admin dashboard
        header("Location: admin-dashboard.php");
        exit(); // Ensure no further code execution after redirection
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:600|Open+Sans:600&display=swap">
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            background-color: #1e1e1e;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            color: white;
            width: 300px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-family: "Montserrat", sans-serif;
            color: white;
        }

        .login-container p {
            color: red;
            margin-bottom: 10px;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #1e1e1e;
            color: white;
        }

        .login-container input[type="submit"] {
            width: 100%;
            background-color: rgb(170, 254, 2);
            color: #1e1e1e;
            border: none;
            padding: 12px 20px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-family: "Montserrat", sans-serif;
            font-weight: 600; 
        }

        .login-container input[type="submit"]:hover {
            color: #1e1e1e;
            background-color: rgb(170, 254, 2);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Authentication</h2>
        <?php
        if (!empty($error_message)) {
            echo '<p>' . $error_message . '</p>';
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
