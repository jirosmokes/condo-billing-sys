<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="stylesheet" href="about-us.css">
    <link rel="icon" href="images/dorm-hub-logo-official-2.png" type="image/png">
</head>
<body>
    <div class="container">
        <!-- Make the logo clickable and link it to landing-page.php -->
        <a href="landing-page.php">
            <img src="images/dorm-hub-logo-official.png" alt="Dormhub Logo" class="logo">
        </a>
        
        <div class="question">
            <h2>What is DormHub?</h2>
            <p><img src="images/dorm-hub-logo-official-2.png" alt="Dormhub Logo" class="icon">
            <span class="typing" data-text="DormHub is an application that lets the owners of a condominium provide an application to their tenants in handling their unit billing transactions."></span></p>
        </div>
        
        <div class="question">
            <h2>What tools were used?</h2>
            <p><img src="images/dorm-hub-logo-official-2.png" alt="Dormhub Logo" class="icon">
            <span class="typing" data-text="For Front-end the team used: HTML 5, CSS 3, and Figma. For Back-end the team used: PHP 8, MySQL and XAMPP."></span></p>
        </div>
        
        <div class="question">
            <h2>Who are the Developers?</h2>
            <p><img src="images/dorm-hub-logo-official-2.png" alt="Dormhub Logo" class="icon">
                > Exequiel Bangal: @Shirahoshii<br>
                > Dennis Lappay: @dLappay<br>
                > Jabenn Tinio: @habenTen<br>
                > Dustin Wania: @jirosmokes<br><br>
                Follow them on GitHub!
            </p>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elements = document.querySelectorAll('.typing');

            elements.forEach(el => {
                const text = el.getAttribute('data-text');
                let index = 0;

                function type() {
                    if (index < text.length) {
                        el.innerHTML += text.charAt(index);
                        index++;
                        setTimeout(type, 50); // Adjust typing speed here
                    }
                }

                type();
            });
        });
    </script>
</body>
</html>
