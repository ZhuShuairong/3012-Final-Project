<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>personal.php</title>
<style>
    body {
        background-image: url("background.png");
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
    }

    .centered {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
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
</style>
</head>
<body>
<div class="container">
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
            <table border="1">
                <tr>
                    <td>userid:</td>
                    <td><input type="text" autocomplete="off" name="userid" size="12" /></td>
                </tr>
                <tr>
                    <td>Old Password:</td>
                    <td><input type="password" autocomplete="off" name="Confirm" size="12" /></td>
                </tr>
                <tr>
                    <td>New Password:</td>
                    <td><input type="password" autocomplete="off" name="Password" size="12" /></td>
                </tr>
            </table>
        </div>
        <div class="centered">
            <a href="logout.php"><button type="button">Logout</button></a>
            &nbsp
            <input type="submit" name="Update" value="Update" />
        </div>
        <a href="index.php" class="back-button">Back to Main Page</a>
    </form>
</div>
</body>
</html>