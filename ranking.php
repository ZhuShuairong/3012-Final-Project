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

// SQL query to select the username and coins columns and order by coins in descending order
$sql = "SELECT username, coins FROM logi ORDER BY coins DESC";

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
        body { font-family: Arial, sans-serif; }
        table.rounded-table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
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
    </style>
</head>
<body>
    <h1>Coin Ranking</h1>
    <table class="rounded-table">
        <tr>
            <th>Rank</th>
            <th>Username</th>
            <th>Coin</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php $rank = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $rank++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['coins']) ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No data found</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
