<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Register</title>
    <?php session_start(); ?>
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
            $username = "";
            $email = "";
            $password = "";
            $confirm = "";
            
            if (isset($_POST["Username"]))
                $username = filter_var($_POST["Username"], FILTER_SANITIZE_STRING);
            if (isset($_POST["Email"]))
                $email = filter_var($_POST["Email"], FILTER_SANITIZE_EMAIL);
            if (isset($_POST["Password"]))
                $password = filter_var($_POST["Password"], FILTER_SANITIZE_STRING);
            if (isset($_POST["Confirm"]))
                $confirm = filter_var($_POST["Confirm"], FILTER_SANITIZE_STRING);
            
            if ($username == "" && $email == "" && $password == "" && $confirm == ""){
            } else {
                if ($username != ""){ 
                    if ($email != ""){
                        if ($password != ""){
                            if ($password == $confirm) {
                                $link = @mysqli_connect(
                                    'localhost',
                                    'root',
                                    'A12345678',
                                    'mydata'
                                );

                                // SQL statement used to add a new record
                                $sql ="INSERT INTO `login-info`(username, email, password) VALUES ('";
                                $sql.=$_POST["Username"]."','".$_POST["Email"]."','";
                                $sql.=$_POST["Password"]."')";
                                echo "<b>SQL command: $sql</b><br/>";
                            
                                mysqli_query($link, 'SET NAMES utf8');
                            
                                if (mysqli_query($link, $sql)) { //Execute SQL instructions
                                    echo "Success!<br/>";
                                    sleep(2);
                                    header("Location: login.php");
                                }
                                mysqli_close($link);
                            } else {
                                $error_message = "Passwords do not match!";
                                echo "<script type='text/javascript'>alert('$error_message');</script>";
                                sleep(2);
                            }
                        } else {
                            $error_message = "Password cannot be empty!";
                            echo "<script type='text/javascript'>alert('$error_message');</script>";
                            sleep(2);
                        }
                    } else {
                        $error_message = "Email cannot be empty!";
                        echo "<script type='text/javascript'>alert('$error_message');</script>";
                        sleep(2);
                    }
                } else {
                    $error_message = "Username cannot be empty!";
                    echo "<script type='text/javascript'>alert('$error_message');</script>";
                    sleep(2);
                }
            }
        ?>

        <form action="register.php" method="POST">
            <h2>Register</h2>
            <table>
                <tr>
                    <td><font size="2">Username:</font></td>
                    <td><input type="text" name="Username" maxlength="12" /></td>
                </tr>
                <tr>
                    <td><font size="2">Email:</font></td>
                    <td><input type="text" name="Email" maxlength="64" /></td>
                </tr>
                <tr>
                    <td><font size="2">Password:</font></td>
                    <td><input type="password" name="Password" maxlength="12" /></td>
                </tr>
                <tr>
                    <td><font size="2">Confirm Password:</font></td>
                    <td><input type="password" name="Confirm" maxlength="12" /></td>
                </tr>
                <tr>
                    <td class="submit-container">
                      <input type="submit" value="Register" />
                      <a href="login.php">Login</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>