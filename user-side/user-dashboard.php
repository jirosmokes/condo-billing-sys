<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../user-style/user-sidebar.css?v=<?php echo time(); ?>">
    <link rel="icon" href="images/dorm-hub-logo-official-2.png" type="image/png">
</head>
<body>
    <input type="checkbox" id="check">
    <label for="check">
        <i class="fas fa-bars" id="btn"></i>
        <i class="fas fa-times" id="cancel"></i>
    </label>

    <div class="sidebar">
        <header><img src="../images/dorm-hub-logo-official.png" alt="" height="30px"></header>

        <a href="#" class="active">
            <i class="fas fa-qrcode"></i>
            <span>Dashboard</span>
        </a>

        <a href="#" onclick="showContent('profile')">
            <i class="fas fa-user-alt"></i>
            <span>Profile</span>
        </a>

        <a href="../user-side/user-billing-information.php" onclick="showContent('bills')">
          <i class="fa-solid fa-money-bills"></i>
            <span>Bills</span>
        </a>

        <a href="#">
            <i class="far fa-question-circle"></i>
            <span>About</span>
        </a>

        <a href="#">
            <i class="fa fa-gear"></i>
            <span>Settings</span>
        </a>

        <a href="../logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>

    <div id="content">
        <!-- Content will be loaded here -->
    </div>

    <script>
        function showContent(section) {
            var contentArea = document.getElementById("content");

            if (section === 'profile') {
                contentArea.innerHTML = `<h2>Profile</h2><form>...</form>`;
            } else if (section === 'bills') {
                contentArea.innerHTML = `<h2>Bills</h2><ul>...</ul>`;
            }
        }
    </script>
</body>
</html>
