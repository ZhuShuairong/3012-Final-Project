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
            margin: 20;
            padding: 0;
            background-color: #e8d7d7;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
        }

        .centered-content {
            text-align: center;
            margin-bottom: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 50px;
        }

        .scrollable-table {
            width: 100%;
            max-height: 400px; /* Set the desired height for the table */
            overflow-y: auto; /* Enable vertical scrolling */
            margin: auto;
        }

        .rounded-table {
            width: 1000px; /* Set the width of each column to 80 pixels */
            font-size: 24px; /* Set the font size to 20 pixels */
            border-collapse: separate;
            border-spacing: 0;
            background-color: #D3BBB8;
            margin: auto;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
        }

        th,
        td {
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
            background-color: rgba(125, 33, 55, 0.8);
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
    Check out your total study time ranking among all users!
</div>

<script>
        window.addEventListener('DOMContentLoaded', function() {
            var popup = document.getElementById('popup');
            popup.style.display = 'block';

            setTimeout(function() {
                popup.style.display = 'none';
            }, 10000); // 10 seconds
        });
</script>
<div class="centered-content">
    <h1 style="margin-top: 0;">Minutes Ranking</h1>
    <div class="container scrollable-table">
        <table class="rounded-table">
            <tr>
                <th style="width: 80px">Rank</th>
                <th style="width: 80px">Username</th>
                <th style="width: 80px">Minutes</th>
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
</div>
<a href="index.php" class="back-button">Back to Main Page</a>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
