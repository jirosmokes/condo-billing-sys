<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>My Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../user-style/user-sidebar.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../images/dorm-hub-logo-official-2.png" type="image/png">
    <style>
        /* #content {
            margin-left: 250px;
            padding: 20px;
            color: white;
        } */

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
        <a href="../user-side/user-dashboard.php " onclick="showContent('profile')">
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
    <?php include 'user-view-profile.php'; ?>
    <div id="content">

    </div>
</body>

</html>