<?php
// Set up database connection variables
$host = 'localhost'; // or your host
$dbname = 'mydata';
$username = 'root';
$password = '';

// Create a mysqli connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select the username and coin columns and order by coin in descending order
$sql = "SELECT username, coins FROM logi ORDER BY coins DESC";

// Execute the query
$result = $conn->query($sql);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . $conn->error);
}

// HTML to display the coins and usernames
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coin Ranking</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Coin Ranking</h1>
    <table>
        <tr>
            <th>Rank</th>
            <th>Username</th>
            <th>Coin</th>
        </tr>
        <?php $rank = 1; ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $rank++ ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['coins']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php
// Close connection
$conn->close();
?>
