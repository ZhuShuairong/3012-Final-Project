<?php
// 清除会话

session_start();
$username = "";

$link = mysqli_connect("localhost", "root", "A12345678", "mydata")
    or die("Cannot open MySQL database connection!<br/>");

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>计时中</title>
</head>
<body>
    <h1>计时中: <span id="focusNameDisplay"></span></h1>
    <div id="timerDisplay">00:00:00</div>
    <button onclick="pauseResumeTimer()">暂停/继续</button>
    <button onclick="stopTimer()">停止计时</button>

    <script>
    let startTime = sessionStorage.getItem('startTime') ? parseInt(sessionStorage.getItem('startTime')) : Date.now();
    let elapsed = sessionStorage.getItem('elapsed') ? parseInt(sessionStorage.getItem('elapsed')) : 0;
    let isPaused = false;
    const focusName = decodeURIComponent(new URLSearchParams(window.location.search).get('name'));
    const timerType = new URLSearchParams(window.location.search).get('type');
    const duration = parseInt(new URLSearchParams(window.location.search).get('duration'), 10) * 60000; // 将分钟转换为毫秒

    document.getElementById('focusNameDisplay').textContent = focusName;

    function updateTimer() {
        if (!isPaused) {
            const now = Date.now();
            elapsed = now - startTime;
            const seconds = Math.floor((elapsed / 1000) % 60);
            const minutes = Math.floor((elapsed / (1000 * 60)) % 60);
            const hours = Math.floor((elapsed / (1000 * 60 * 60)) % 24);
            document.getElementById('timerDisplay').textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timerType === 'down' && elapsed >= duration) {
                clearInterval(timerInterval);
                alert('倒计时结束');
            }
        }
    }

    let timerInterval = setInterval(updateTimer, 1000);

    function pauseResumeTimer() {
        isPaused = !isPaused;
        if (isPaused) {
            clearInterval(timerInterval);
            sessionStorage.setItem('startTime', startTime);
            sessionStorage.setItem('elapsed', elapsed);
            window.location.href = 'pause.php';
        } else {
            startTime = Date.now() - elapsed;
            timerInterval = setInterval(updateTimer, 1000);
        }
    }

    function stopTimer() {
        clearInterval(timerInterval);
        sessionStorage.removeItem('startTime');
        sessionStorage.removeItem('elapsed');
        const currentDate = new Date().toISOString().split('T')[0];
        window.location.href = `summary.php?name=${encodeURIComponent(focusName)}&date=${currentDate}&elapsed=${elapsed}`;
    }

    // 检查11、1和4是否存在于$_SESSION['selected_product_ids']数组中
    const selectedProductIds = <?php echo json_encode($_SESSION['selected_product_ids']); ?>;
    if (selectedProductIds.includes("11") && selectedProductIds.includes("1") && selectedProductIds.includes("5")) {
        print("hello");
    }
    </script>
</body>
</html>