<?php
// 连接到数据库
$link = mysqli_connect("localhost", "root", "A12345678", "mydata")
    or die("Cannot open MySQL database connection!<br/>");

// 获取当前用户的coins数量
session_start();
$userid = $_SESSION["userid"]; // 使用会话变量userid
$sql = "SELECT coins FROM `login-info` WHERE userid = '$userid'";
$result = mysqli_query($link, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentCoins = $row['coins'];

    // 将coins值作为响应发送回客户端
    echo $currentCoins;
} else {
    // 处理未找到记录的情况
    echo "No records found for the user.";
}

// 关闭数据库连接
mysqli_close($link);
?>