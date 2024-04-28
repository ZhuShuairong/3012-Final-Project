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
                                    require_once "Mail.php";
                                    $from = "tonysam@um.edu.mo";
                                    $to = $email;
                                    $subject = "🎉 Registration Success! 🎉";

                                    // HTML email body
                                    $body = <<<HTML
                                    <!DOCTYPE html>
                                    <html lang="en">
                                    <head>
                                        <meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                        <title>Registration Success</title>
                                    </head>
                                    <body style="font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 20px;">

                                        <h1 style="color: #007bff;">Thank you for registering!</h1>
                                        <p style="font-size: 16px;">We're excited to have you on board.</p>

                                        <p style="font-size: 16px;">Click the button below to log in:</p>
                                        <a href="http://localhost:3000/3012-Final-Project-main/login.php" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">Log In</a>

                                        <p style="font-size: 14px; color: #888;">If you didn't register, please ignore this email.</p>

                                        <p style="font-size: 14px; color: #888;">Best regards,<br>Your Website Team</p>
                                    </body>
                                    </html>
                                    HTML;

                                    // SMTP configuration
                                    $host = "smtp.um.edu.mo";
                                    $port = "587";
                                    $username = "smtpshare";
                                    $password = "T&es34Y+";

                                    // Additional headers
                                    $extraHeaders = array(
                                        'From' => $from,
                                        'To' => $to,
                                        'Subject' => $subject,
                                        'Content-Type' => 'text/html; charset=UTF-8', // Set the Content-Type header
                                    );

                                    // Create the SMTP instance
                                    $smtp = Mail::factory('smtp', array(
                                        'host' => $host,
                                        'port' => $port,
                                        'auth' => true,
                                        'socket_options' => array(
                                            'ssl' => array(
                                                'verify_peer' => false,
                                                'verify_peer_name' => false,
                                                'allow_self_signed' => true,
                                            ),
                                        ),
                                        'username' => $username,
                                        'password' => $password,
                                    ));

                                    // Send the email
                                    $mail = $smtp->send($to, $extraHeaders, $body);

                                    if (PEAR::isError($mail)) {
                                        echo("<p>" . $mail->getMessage() . "</p>");
                                    } else {
                                        echo("<p>Message successfully sent!</p>");
                                    }

                                    // Redirect to login.php
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