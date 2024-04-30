<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <style>
        body {
            background-image: url("background.jpg");
            background-repeat: no-repeat;
            background-size: auto 100vh;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.7);
            width: 400px;
            margin: 0 auto;
            margin-top: 200px;
            padding: 20px;
            text-align: center;
            border-radius: 10px; /* 添加圆角边框 */
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2); /* 添加阴影效果 */
        }

        .login-container h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .login-container table {
            margin: 0 auto;
        }

        .login-container td {
            padding: 10px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc; /* 添加边框 */
            border-radius: 4px; /* 添加圆角边框 */
        }

        .login-container .submit-container {
            margin-top: 20px;
        }

        .login-container input[type="submit"],
        .register-button {
            background-color: #4CAF50; /* 使用绿色按钮 */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.4);
            display: inline-block;
            margin-top: 10px;
            font-size: 16px;
        }

        .login-container input[type="submit"]:hover,
        .register-button:hover {
            background-color: #45a049; /* 鼠标悬停时的绿色按钮 */
            box-shadow: none;
        }

        /* 调整按钮之间的距离 */
        .login-container .register-button {
            margin-left: 25px;
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

    <h2>Login</h2>
    <formaction="login.php" method="post">
        <table>
            <tr>
                <td><label for="username">Username:</label></td>
                <td><input type="text" id="username" name="Username" maxlength="12" /></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input type="password" id="password" name="Password" maxlength="12" /></td>
            </tr>
            <tr>
                <td class="submit-container" colspan="2">
                    <input type="submit" name="submit" value="Log in" />
                    <button type="button" onclick="location.href='register.php'" class="register-button">Register</button>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>