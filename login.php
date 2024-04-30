<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <style>
        body {
            background-image: url("background.png");
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .login-container {
            background-color: rgba(255, 255, 255, 0.7);
            width: 400px;
            margin: 0 auto;
            margin-top: 200px;
            padding: 20px;
            text-align: center;
        }
        
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        .login-container .submit-container {
            text-align: center;
        }
        
        .login-container input[type="submit"] {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.4);
        }
        
        .login-container input[type="submit"]:hover {
            background-color: darkorange;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php
        session_start();
        $username = "";
        $password = "";

        if (isset($_POST["Username"]))
            $username = $_POST["Username"];
        if (isset($_POST["Password"]))
            $password = $_POST["Password"];

        if (isset($_POST["submit"])) {
            if ($username == "") {
                echo "<script>alert('Please enter a username!');</script>";
                $_SESSION["login_session"] = false;
            } elseif ($password == "") {
                echo "<script>alert('Please enter a password!');</script>";
                $_SESSION["login_session"] = false;
            } else {
                $link = @mysqli_connect(
                    'localhost',
                    'root',
                    'A12345678',
                    'mydata'
                );

                mysqli_query($link, 'SET NAMES utf8');

                $sql = "SELECT * FROM `login-info` WHERE password='";
                $sql .= $password . "' AND username='" . $username . "'";

                $result = mysqli_query($link, $sql);
                $total_records = mysqli_num_rows($result);

                if ($total_records > 0) {
                    $sql = "SELECT userid FROM `login-info` WHERE password='";
                    $sql .= $password . "' AND username='" . $username . "'";

                    $result = mysqli_query($link, $sql);
                    $userid = mysqli_fetch_row($result)[0];

                    $_SESSION["userid"] = $userid;
                    $_SESSION["login_session"] = true;
                    header("Location: index.php");
                } else {
                    echo "<script>alert('Username or password not found!');</script>";
                    $_SESSION["login_session"] = false;
                }
                mysqli_close($link);
            }
        }
        ?>

        <form action="login.php" method="post">
            <h2>Login</h2>
            <table>
                <tr>
                    <td><font size="2">Username:</font></td>
                    <td><input type="text" name="Username" maxlength="12" /></td>
                </tr>
                <tr>
                    <td><font size="2">Password:</font></td>
                    <td><input type="password" name="Password" maxlength="12" /></td>
                </tr>
                <tr>
                    <td class="submit-container">
                      <input type="submit" name="submit" value="Log in" />
                      <a href="register.php">Register</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>