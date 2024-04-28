<?php
// Start the session
session_start();

$link = @mysqli_connect('localhost', 'root', 'A12345678', 'mydata');

// Check if the connection was successful
if (!$link) {
    die('Could not connect to the database: ' . mysqli_connect_error());
}

// Function to check if a message contains a swear word
function containsSwearWord($message, $swearWords) {
    foreach ($swearWords as $word) {
        if (stripos($message, $word) !== false) {
            return true;
        }
    }
    return false;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Back'])) {
        header("Location: index.php");
    }

    // Get the message from the text box
    $message = $_POST['message'];

    // Read the swear words from the CSV file
    $swearWords = file('swear words.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Check if the message is blank or contains a swear word
    if (empty($message)) {
        $error_message = "Message cannot be blank!";
        echo "<script type='text/javascript'>alert('$error_message');</script>";
    } elseif (containsSwearWord($message, $swearWords)) {
        $error_message = "Please do not use profanity!";
        echo "<script type='text/javascript'>alert('$error_message');</script>";
    }else {
        if (isset($_SESSION['userid'])) {
            $sql = "SELECT username FROM `login-info` WHERE userid='" . $_SESSION['userid'] . "'";
            $result = mysqli_query($link, $sql);
            $username = mysqli_fetch_row($result)[0];
        }

        // Get the current datetime
        $datetime = date('Y-m-d H:i:s');

        // Insert the message into the database
        $insertQuery = "INSERT INTO `public-forum` (username, message, datetime) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($link, $insertQuery);
        mysqli_stmt_bind_param($stmt, 'sss', $username, $message, $datetime);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect the user to the forum page to see the updated messages
            mysqli_stmt_close($stmt);
            header('Location: forum.php');
            exit();
        } else {
            echo 'Error inserting message into the database.';
        }
        mysqli_stmt_close($stmt);
    }
}

// Construct the SQL query
$query = "SELECT * FROM `public-forum` WHERE datetime >= DATE_SUB(NOW(), INTERVAL 10 DAY) ORDER BY datetime DESC";

// Execute the query
$result = mysqli_query($link, $query);

// Check if there are any messages
if (mysqli_num_rows($result) > 0) {
    // Output each message
    echo '<div class="message-container">';
    while ($row = mysqli_fetch_assoc($result)) {
        $message = $row['message'];
        $username = $row['username'];
        $datetime = $row['datetime'];

        // Display the message
        echo '<p style="font-size: 20px;">' . $message . '</p>';
        echo '<p style="font-size: 12px;">' . $username . "  |  " . $datetime . '</p>';
        echo '<hr>';
    }
    echo '</div>';
} else {
    echo 'No messages found.';
}

// Close the database connection
mysqli_close($link);
?>

<!-- HTML form for user input -->
<form method="POST" style="display: flex; position: fixed; bottom: 0; left: 0; width: 100%; margin-bottom: 60px;">
    <textarea name="message" rows="4" style="flex: 1; padding: 0 20px; margin-left: 10px; margin-right: 10px;" placeholder="Share a message:"></textarea>
    <input type="submit" value="Send" style="flex: 0.05;"> <!-- Modified the flex value to make it smaller -->
</form>
<div class="dropdown">
    <button class="dropbtn">Menu</button>
    <div class="dropdown-content">
        <a href="index.php">Back</a>    
        <a href="shop.php">Shop</a>
        <a href="records.php">Record</a>
        <a href="choose.php">choose</a>
        <a href="personal.php">Personal</a>
    </div>
</div>
<style>
    .message-container {
        max-height: calc(100vh - 180px);
        overflow-y: auto;
    }
    .dropdown {
        position: fixed;
        top: 20px;
        right: 20px;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        right: 0;
    }
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }
    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }
    .dropdown:hover .dropdown-content {
        display: block;
    }
    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }
</style>