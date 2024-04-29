<?php
session_start();
require_once 'db_connect.php';

$userId = $_SESSION['userid'] ?? 'default_userid'; // 从会话中获取用户ID

// 获取当前时间和上次更新时间
$sql = "SELECT coins, UNIX_TIMESTAMP(last_update) AS last_update_unix FROM `login-info` WHERE userid = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $lastUpdate = $row['last_update_unix'];
    $currentCoins = $row['coins'];
    $currentTime = time();

    // 检查是否已经过了三分钟
    if ($currentTime - $lastUpdate >= 180) {
        // 更新硬币数和上次更新时间
        $sql = "UPDATE `login-info` SET coins = coins + 1, last_update = FROM_UNIXTIME(?) WHERE userid = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ii", $currentTime, $userId);
        $stmt->execute();

        // 更新硬币计数以显示增加的硬币
        $currentCoins += 1;
    }

    // 输出最新的硬币数
    echo json_encode([$currentCoins]);
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$link->close();
?>

