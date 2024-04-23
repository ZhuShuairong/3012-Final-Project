<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <style>
        body {
            background-image: url("background.png");
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .white-background {
            background-color: rgba(255, 255, 255, 0.7);
            width: 400px;
            margin: 0 auto;
            margin-top: -170px;
            padding: 20px;
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="white-background">
    <div>
        <img src="loading.gif" alt="Loading" />
    </div>
    <div id="time">
        <?php
        date_default_timezone_set('Asia/Hong_Kong');
        echo date('F j, Y, g:i a');
        ?>
    </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = 'login.php'; // Redirect to login/registration page after 4 seconds
        }, 3000);
    </script>
</body>
</html>