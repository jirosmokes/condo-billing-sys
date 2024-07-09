<?php
session_start();
if (empty($_SESSION['account_number'])) {
    header("Location: ../landing-page.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/dorm-hub-logo-official-2.png" type="image/png">

    <title>DH-Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
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
    <div id="content">
        <?php include 'admin-dbContent.php'; ?>
    </div>
</body>
</html>