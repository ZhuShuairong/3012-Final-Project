<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>Focus Summary</title>
    <style>
        body {
            background-image: url("summary_background.jpg");
            background-size: cover;
            background-position: top;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            animation: fade-in 1s ease-in-out;
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        table {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 8px;
            width: 600px;
            max-width: 100%;
        }

        table td {
            color: #333;
            font-size: 20px;
            padding: 10px 20px;
        }

        .highlight {
            color: orange;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #44C767;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #5CBF88;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
<?php
session_start();

if (isset($_GET['name']) && isset($_GET['date']) && isset($_GET['elapsed']) && isset($_GET['coins'])) {
    $name = urldecode($_GET['name']);
    $date = $_GET['date'];
    $elapsed = intval($_GET['elapsed']);  // Convert elapsed time to integer
    $coins = intval($_GET['coins']);  // Get coins from the query string

    $hours = floor($elapsed / 3600000);
    $minutes = floor(($elapsed % 3600000) / 60000);
    $seconds = floor(($elapsed % 60000) / 1000);

    echo "<table>";
    echo "<tr><td colspan='2'><h1>Focus Summary</h1></td></tr>";
    echo "<tr><td>Date:</td><td><span class='highlight'>$date</span></td></tr>";
    echo "<tr><td>Time Spent:</td><td><span class='highlight'>$hours h $minutes m $seconds s</span></td></tr>";
    echo "<tr><td>Task:</td><td><span class='highlight'>$name</span></td></tr>";
    echo "<tr><td>Coins Obtained:</td><td><span class='highlight'>$coins coins</span></td></tr>";
    echo "<tr><td colspan='2' style='text-align: center; color: transparent;'>\n</td></tr>";
echo "<tr><td colspan='2' style='text-align: center; color: transparent;'><a class='button' href='Index.php'>Back to Index</a></td></tr>";
} else {
    echo "<p>Focus record format is incorrect or incomplete.</p>";
}
?>

</body>
</html>
