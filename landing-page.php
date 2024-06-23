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
                <form class="login-form" action="/login" method="POST">
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Account Number" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit">Login</button>
                    </div>
                    <div class="forgot">
                        <small><a href="#">Forgot Password?</a></small>
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
