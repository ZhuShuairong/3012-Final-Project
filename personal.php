<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>personal.php</title>
</head>
<body>
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
        } else
            die("Database update record failed<br/>");
    } else 
        echo "Old password incorrect!";
    mysqli_close($link);
} elseif (isset($_POST["Back"])) {
    header ("Location:index.php");
}
?>
<form action="personal.php" method="post">
<table border="1">
<tr>
    <td>userid:</td>
    <td><input type="text" name="userid" size ="12"/></td>
</tr>
<tr>
    <td>Old Password:</td>
    <td><input type="password" name="Confirm" size="12"/></td>
</tr>
<tr>
    <td>New Password:</td>
    <td><input type="password" name="Password" size="12"/></td>
</tr>
</table><hr/>
<input type="submit" name="Update" value="Update"/>
<input type="submit" name="Back" value="Back"/>
</form>
</body>
</html>