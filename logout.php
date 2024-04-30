<?php
// Clear session variables
session_start();
session_unset();
session_destroy();

// Clear cookies
setcookie('username', '', time() - 3600, '/');
setcookie('password', '', time() - 3600, '/');

// Redirect to login.php
header("Location: login.php");
exit();
?>