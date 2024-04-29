<?php
// 数据库服务器信息
$servername = "localhost";
$username = "root";
$password = "";  // 实际部署时请确保使用安全的密码
$dbname = "mydata";         // 你的数据库名称

// 创建连接
$link = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($link->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 这里不需要关闭连接，因为它会在脚本执行完毕后自动关闭
?>
