<?php
// Start session if it's not already started
session_start();

// Check if session variable for admin login is not set, redirect to login page
if (!isset($_SESSION['account_number'])) {
    header("Location: ../landing-page.php");
    exit();
}

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
        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background: linear-gradient(145deg, #1e1e1e, #2c2c2c);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar header {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 70px;
            background: #222;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px 30px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar a.active {
            background-color: #575757;
        }

        .sidebar a i {
            margin-right: 10px;
        }

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

        /* Content styles */
        #content {
            margin-left: 250px;
            padding: 20px;
            color: white;
        }

        body {
            margin: 0;
            font-family: "Open Sans", sans-serif;
            background-color: #1e1e1e;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <header><img src="../images/dorm-hub-logo-official.png" alt="" height="30px"></header>

    <a href="../admin-side/admin-dbcontent.php" class="active">
        <i class="fas fa-qrcode"></i>
        <span>Dashboard</span>
    </a>

    <a href="../admin-side/admin-room-selection.php">
        <i class="fas fa-user-alt"></i>
        <span>View Tenants</span>
    </a>
    
    <a href="../admin-side/admin-transaction.php">
        <i class="fas fa-user-alt"></i>
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
        <?php include 'admin-dbContent.php'; ?>
    </div>
</body>
</html>
