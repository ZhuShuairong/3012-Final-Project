<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>personal.php</title>
<style>
    body {
        background-image: url("background_personal.png");
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: transparent;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 10px;
        max-width: 1000px;
        box-shadow: 0 0 10px white;
        font-size: 20px;
    }

    .centered {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 10px;
        color: black;
    }
    .back-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #fff;
        color: black;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 20px;
        font-weight: bold;
    }
    .update-button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #fff;;
        color: black;
        border-radius: 20px;
        border: none;
        cursor: pointer;
        margin-left: auto;
        margin-right: auto;
        display: block;
        font-weight: bold;
    }
    .title {
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 20px;
        color: white;
    }
    .right-aligned-label {
        display: flex;
        justify-content: flex-end;
        width: 100px;
        margin-right: 10px;
    }
    .input-field {
        display: flex;
        align-items: flex-end;
        justify-content: flex-end;
        margin-top: 10px;
    }
    .input-field input[type="text"],
    .input-field input[type="password"] {
        background-color: transparent; 
        border: 1px solid white; 
        color: white; 
    }
    label {
        margin-right: 10px; 
        text-align: right;
    }
</style>
</head>
<body>
<div class="container">
    <div class="title">Change Password</div>
    <?php
    // check if Update button was clicked
    if (isset($_POST["Update"])) {
        $link = @mysqli_connect(
            'localhost',
            'root',
            'A12345678',
            'mydata'
        ) or die("Unable to open MySQL database connection!<br/>");

        //define sql string used to update record
        $sql = "UPDATE `login-info` SET ";
        $sql.= "password='".$_POST["Password"]."'";
        $sql.= " WHERE userid = '".$_POST["userid"]."'";
        echo "<b>SQL command: $sql</b><br/>";

        $confirmsql = "SELECT * FROM `login-info` WHERE password='";
        $confirmsql.= $_POST["Password"]."'";

        mysqli_query($link, 'SET NAMES utf8');
        if (mysqli_query($link, $confirmsql)) {
            if ( mysqli_query($link, $sql) ) {
                echo "Database update record successful<br/>";
            } else {
                die("Database update record failed<br/>");
            }
        } else {
            echo "Old password incorrect!";
        }
        mysqli_close($link);
    } elseif (isset($_POST["Back"])) {
        header("Location: index.php");
    }
    ?>
    <form action="personal.php" method="post">
        <div class="centered">
            <label for="userid" style="text-align: left;">User ID:</label>
            <input type="text" autocomplete="off" name="userid" id="userid" size="12" /><br>
        </div>
        <div class="centered">
            <label for="confirm" style="text-align: left;">Old Password:</label>
            <input type="password" autocomplete="off" name="Confirm" id="confirm" size="12" /><br>
        </div>
        <div class="centered">
            <label for="password" style="text-align: left;">New Password:</label>
            <input type="password" autocomplete="off" name="Password" id="password" size="12" /><br>
        </div>
        <div class="centered">
            <button type="submit" name="Update" class="update-button">Update</button>
        </div>
        <a href="index.php" class="back-button">Back to Main Page</a>
    </form>
</div>
</body>
</html>