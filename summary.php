<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>专注总结</title>
</head>
<body>
<?php
session_start();

if (isset($_GET['name']) && isset($_GET['date']) && isset($_GET['elapsed'])) {
    $name = urldecode($_GET['name']);
    $date = $_GET['date'];
    $elapsed = intval($_GET['elapsed']);  // Convert elapsed time to integer

    $hours = floor($elapsed / 3600000);
    $minutes = floor(($elapsed % 3600000) / 60000);
    $seconds = floor(($elapsed % 60000) / 1000);

    echo "<h1>专注总结</h1>";
    echo "<p>在 {$date}，进行 {$name} 进行了 {$hours}小时{$minutes}分钟{$seconds}秒。</p>";
} else {
    echo "<p>专注记录格式不正确或不完整。</p>";
}
?>

</body>
</html>
