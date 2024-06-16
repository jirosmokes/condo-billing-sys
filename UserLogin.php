<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DormHub | Login</title>
    <link rel="stylesheet" href="UserLogin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" 
     type="image/png" 
     href="logo.png">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="logo.png" alt="Company Logo">
            <h1>DormHub</h1>
        </div>
        <div class="inside-container">
            <h1>Hello</h1>
            <form class="login-form" action="/login" method="POST">
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Account Number" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="forgot">
					<small><a href="#">Forgot Password?</a></small>
				</div>
                <div class="form-group">
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

