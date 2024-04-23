<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>查看记录</title>
</head>
<body>
    <h1>查看专注记录</h1>
    <?php
    if (isset($_COOKIE['lastSession'])) {
        list($name, $date, $timeSpent) = explode(';', $_COOKIE['lastSession']);
        $hours = floor($timeSpent / 3600000);
        $minutes = floor(($timeSpent % 3600000) / 60000);
        $seconds = floor(($timeSpent % 60000) / 1000);
        echo "<p>记录：在 " . htmlspecialchars($date) . "，进行 " . htmlspecialchars($name) . " 进行了 {$hours}小时{$minutes}分钟{$seconds}秒。</p>";
    } else {
        echo "<p>没有找到任何记录。</p>";
    }
    ?>
    <a href="index.php" class="back-button">Back to Main Page</a>
</body>
</html>
