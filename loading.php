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
        
        .loading-bar-container {
            width: 200px;
            height: 10px;
            background-color: #eee;
            border-radius: 5px;
            overflow: hidden;
            margin: 20px auto;
        }
        
        .loading-bar-fill {
            width: 0;
            height: 100%;
            background-color: green;
            border-radius: 5px;
            animation: fill-loading-bar 3s linear forwards;
        }
        
        @keyframes fill-loading-bar {
            0% {
                width: 0;
            }
            50% {
                width: 50%;
            }
            80% {
                width: 80%;
            }
            100% {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="white-background">
        <div>
            <div class="loading-bar-container">
                <div class="loading-bar-fill"></div>
            </div>
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