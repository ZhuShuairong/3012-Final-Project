<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Register</title>
    <?php session_start(); ?>
    <style>
        body {
            background-image: url("register_background.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .register-container {
            background-color: rgba(255, 255, 255, 0.5);
            width: 600px;
            padding: 20px;
            text-align: center;
            border-radius: 20px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.4);
        }
        
        table {
            width: 80%; /* Adjusted table width for better proximity of labels to inputs */
            margin: auto; /* Ensure table is centered within container */
        }

        td {
            text-align: left;
            padding: 8px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%; /* Adjust input width to fill table cell */
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
        }
        
        .submit-container {
    text-align: center; /* Ensures button alignment is centralized in container */
    width: 100%; /* Full width to spread across the container */
    padding-top: 20px; /* Space above buttons */
}

.button-group {
    display: flex;
    justify-content: center; /* Centering buttons horizontally */
    gap: 25px; /* 25px gap between buttons */
}

        
        input[type="submit"],
        button.login-button {
            background-color: #1aad19;
            color: white;
            padding: 12px 24px;
            border: none;
            cursor: pointer;
            border-radius: 10px;
            font-size: 16px;
        }
        
        input[type="submit"]:hover,
        button.login-button:hover {
            background-color: #138e15;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <?php
            $username = "";
            $email = "";
            $password = "";
            $confirm = "";
            $link = @mysqli_connect('localhost', 'root', 'A12345678', 'mydata');
            
            if (isset($_POST["Username"]))
                $username = filter_var($_POST["Username"], FILTER_SANITIZE_STRING);
            if (isset($_POST["Email"]))
                $email = filter_var($_POST["Email"], FILTER_SANITIZE_EMAIL);
            if (isset($_POST["Password"]))
                $password = filter_var($_POST["Password"], FILTER_SANITIZE_STRING);
            if (isset($_POST["Confirm"]))
                $confirm = filter_var($_POST["Confirm"], FILTER_SANITIZE_STRING);

            $sql ="SELECT * FROM `login-info`";
            $result = $link->query($sql);
            
            $repeat = false;
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($username == $row['username']){
                        $repeat = true;
                    }
                }
            }
            if (!isset($_POST["Register"])){
                goto jmp;
            } elseif ($username == ""){ 
                $error_message = "Username cannot be empty!";
                    echo "<script type='text/javascript'>alert('$error_message');</script>";
                    goto jmp;
            } elseif ($repeat == true){
                $error_message = "Username already in use!";
                    echo "<script type='text/javascript'>alert('$error_message');</script>";
                    goto jmp;
            } elseif ($email == ""){
                $error_message = "Email cannot be empty!";
                    echo "<script type='text/javascript'>alert('$error_message');</script>";
                    goto jmp;
            } elseif ($password == ""){
                $error_message = "Password cannot be empty!";
                    echo "<script type='text/javascript'>alert('$error_message');</script>";
                    goto jmp;
            } elseif ($password != $confirm) {
                $error_message = "Password don't match!";
                    echo "<script type='text/javascript'>alert('$error_message');</script>";
                    goto jmp;
            } else {
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
                jmp:
            }
            ?>

<form action="register.php" method="POST">
            <h2>Register</h2>
            <table>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" name="Username" maxlength="12" /></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="text" name="Email" maxlength="64" /></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="Password" maxlength="12" /></td>
                </tr>
                <tr>
                    <td>Confirm Password:</td>
                    <td><input type="password" name="Confirm" maxlength="12" /></td>
                </tr>
                <tr>
                    <td colspan="2" class="submit-container">
                        <div class="button-group">
                            <input type="submit" name="Register" value="Register" />
                            <button type="button" class="login-button" onclick="window.location.href='login.php'">Login</button>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>