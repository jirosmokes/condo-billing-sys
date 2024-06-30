<?php
// Start session if it's not already started
session_start();

// Check if session variable for admin login is not set, redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-landing.php");
    exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to landing page after logout
    header("Location: admin-landing.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DH-Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../user-style/user-sidebar.css?v=<?php echo time(); ?>">
    <style>
        /* Additional styles to ensure logout button looks like other sidebar links */
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
    </style>
</head>
<body style="background-color: #1e1e1e;">
    <input type="checkbox" id="check">
    <label for="check">
        <i class="fas fa-bars" id="btn"></i>
        <i class="fas fa-times" id="cancel"></i>
    </label>

    <div class="sidebar">
        <header><img src="../images/dorm-hub-logo-official.png" alt="" height="30px"></header>

        <a href="#" class="active" onclick="showContent('dashboard')">
            <i class="fas fa-qrcode"></i>
            <span>Dashboard</span>
        </a>

        <a href="#" onclick="showContent('view-tenants')">
            <i class="fas fa-user-alt"></i>
            <span>View Tenants</span>
        </a>

        <a href="#" onclick="showContent('view-revenue')">
            <i class="fa-solid fa-money-bills"></i>
            <span>Revenue</span>
        </a>

        <a href="#">
            <i class="far fa-question-circle"></i>
            <span>About</span>
        </a>

        <form method="post" action="" style="margin-top: auto;">
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div id="content">
        <!-- Content will be loaded here -->
    </div>

    <script>
        function showContent(section) {
            var contentArea = document.getElementById("content");

            if (section === 'dashboard') {
                contentArea.innerHTML = `<h2>Dashboard</h2>`;
            } else if (section === 'view-tenants') {
                contentArea.innerHTML = `<?php include '../admin-side/admin-room-selection.php'?>`;
            } else if (section === 'view-revenue') {
                contentArea.innerHTML = `<h2>Revenue</h2>`;
            }
        }
    </script>
</body>
</html>
