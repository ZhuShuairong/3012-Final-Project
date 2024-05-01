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
        header("Location: forum.php");
    }
}

// Construct the SQL query
$query = "SELECT * FROM `public-forum` WHERE datetime >= DATE_SUB(NOW(), INTERVAL 10 DAY) ORDER BY datetime DESC";

// Execute the query
$result = mysqli_query($link, $query);

// Check if there are any messages
if (mysqli_num_rows($result) > 0) {
    // Output each message
    $messages = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $message = $row['message'];
        $username = $row['username'];
        $datetime = $row['datetime'];

        // Display the message
        $messages .= '<p style="font-size: 20px;">' . $message . '</p>';
        $messages .= '<p style="font-size: 12px;">' . $username . "  |  " . $datetime . '</p>';
        $messages .= '<hr>';
    }
} else {
    $messages = 'No messages found.';
}

// Close the database connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(to bottom, #d39dbb, #ded6f2);
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 5px solid black;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            width: 25cm;
            height: 15cm;
            position: relative;
            box-sizing: border-box; 
            border: 10px solid black;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .message-container {
            position: relative;
            width: 800px;
            padding: 20px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            z-index: 2;
        }
        .message-container p {
            font-size: 25px;
            margin-top: 0;
        }
        .message-container p:last-child {
            font-size: 17px;
        }
        .form-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center; 
            justify-content: center;
            width: 940px; 
            margin-top: auto; 
        }
        .form-container textarea {
            position: relative; 
            bottom: -80px; 
            width: 860px; 
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px; 
            margin-left: 24px;
            border: none;
            background-color: #f9f9f9;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        .form-container input[type="submit"] {
            position: absolute;
            bottom: -75px; 
            left: 50%;
            transform: translateX(-50%);
            width: 100px; 
            height: 32px; 
            border: none; 
            border-radius: 60px; 
            background-image: linear-gradient(to right, #d39dbb, #ded6f2);
            color: white;
            font-size: 17px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .dropdown {
            position: fixed;
            top: 20px;
            right: 20px;
            border: none; 
            outline: none; 
        }
        .dropbtn {
        font-size: 20px;
        padding: 10px 20px; 
        border-radius: 20px;
        background-color: purple;
        color: white;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
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
            background-color:purple;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown:hover .dropbtn {
            background-color: purple;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message-container">
            <?php echo $messages; ?>
        </div>

        <!-- HTML form for user input -->
        <div class="form-container">
            <form method="POST">
                <textarea name="message" rows="4" placeholder="Share a message:"></textarea>
                <input type="submit" value="+ Send">
            </form>
        </div>
        
        <div class="dropdown">
            <button class="dropbtn">Menu</button>
            <div class="dropdown-content">
                <a href="index.php">Back</a>    
                <a href="shop.php">Shop</a>
                <a href="ranking.php">Ranking</a>
                <a href="unlock.php">Unlock</a>
                <a href="personal.php">Personal</a>
            </div>
        </div>
    </div>
</body>
</html>