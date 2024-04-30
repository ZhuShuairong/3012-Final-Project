<?php
// Set up database connection variables
$host = 'localhost'; // or your host
$dbname = 'mydata';
$username = 'root';
$password = 'A12345678';

// Create a mysqli connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select the username and minutes columns and order by coins in descending order
$sql = "SELECT username, minutes FROM `login-info` ORDER BY minutes DESC";

// Execute the query
$result = $conn->query($sql);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coin Ranking</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e8d7d7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .centered-content {
            text-align: center;
        }

        table.rounded-table { 
            max-width: 1000px;
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            background-color: #D3BBB8;
            margin: auto;
        }

        .container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 20px; /* 设置左右间距为 20px */
    }

            th, td { 
                border: 1px solid #ddd; 
                padding: 8px; 
                text-align: center;
                background-color: #fff;
            }

            th { 
                background-color: #f2f2f2;
            }

            tr:first-child th:first-child {
                border-top-left-radius: 10px;
            }

            tr:first-child th:last-child {
                border-top-right-radius: 10px;
            }

            tr:last-child td:first-child {
                border-bottom-left-radius: 10px;
            }

            tr:last-child td:last-child {
                border-bottom-right-radius: 10px;
            }

            .centered-heading {
                margin-bottom: 150px;
            }

        .back-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ccc;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        /* Styles for the popup window */
        #popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(125, 33, 55, 0.8);;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            z-index: 9999; /* Higher z-index value */
            opacity: 1;
            transition: opacity 1ms ease; /* Transition effect for opacity property */
        }
    </style>
</head>
<body>
<div id="popup">
    查看你在所有用户中的总学习时长排名！
</div>

<script>
        window.addEventListener('DOMContentLoaded', function() {
            var popup = document.getElementById('popup');
            popup.style.display = 'block';

            setTimeout(function() {
                popup.style.display = 'none';
            }, 5000); // 10 seconds
        });
</script>

<div class="centered-content">
        <h1 class="centered-heading">Minutes Ranking</h1>
        <div class="container">
        <table class="rounded-table">
        <tr>
            <th>Rank</th>
            <th>Username</th>
            <th>Minutes</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php $rank = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $rank++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['minutes']) ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No data found</td>
            </tr>
        <?php endif; ?>
    </table>
    </div>
    <a href="index.php" class="back-button">Back to Main Page</a>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
