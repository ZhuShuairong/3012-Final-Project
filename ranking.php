<?php
// Set up database connection variables
$host = 'localhost'; // or your host
$dbname = 'root';
$username = 'A12345678';
$password = 'mydata';



// Create a PDO instance to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// SQL query to select the username and coin columns and order by coin in descending order
$sql = "SELECT username, coin FROM tablename ORDER BY coin DESC";

// Prepare and execute SQL statement
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
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
        <?php foreach ($results as $row): ?>
        <tr>
            <td><?= $rank++ ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['coin']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
