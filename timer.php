<?php
if (!session_id()) session_start();
require_once 'db_connect.php'; // 确保你有一个用于数据库连接的文件

// 获取当前选择的产品 ID
$selected_product_id = isset($_GET['background']) ? $_GET['background'] : null;

// 查询数据库以获取当前选择产品对应的背景图片 URL
if ($selected_product_id) {
    $sql = "SELECT mime, name FROM `background` WHERE bg_id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("i", $selected_product_id);
    $stmt->execute();
    $stmt->bind_result($mime, $name);
    $stmt->fetch();
    $stmt->close();

    if ($name) {
        echo "成功！已读取到选定的产品 ID";

        // 根据 MIME 类型确定图片的后缀
        $extension = 'jpg'; // 默认后缀为 jpg
        if ($mime === 'image/png') {
            $extension = 'png';
        } elseif ($mime === 'image/gif') {
            $extension = 'gif';
        }

        // 根据产品 ID 和后缀生成背景图片 URL
        $backgroundUrl = "3012 final picture/$mime";
    } else {
        echo "未读取到选定的产品 ID的产品名称";
    }
} else {
    echo "未读取到选定的产品 ID";

    // 如果没有选择产品或选择的产品 ID 无效，使用默认背景图片URL
    $backgroundUrl = 'default_background.jpg'; // 设置一个默认的背景图片URL
}



// 获取前端传来的参数
$name = isset($_GET['name']) ? $_GET['name'] : 'default_name';
$date = $_GET['date'] ?? date('Y-m-d'); // 如果没有date，使用当前日期
$elapsed = $_GET['elapsed'] ?? 0;
$userId = $_SESSION['userid'] ?? 'default_userid'; // 假设有默认值或处理用户未登录的情况

// 计算获得的硬币数
$coins = floor($elapsed / 180); // 每三分钟获得一个硬币

// SQL数据库更新逻辑
$link = new mysqli($servername, $username, $password, $dbname);
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

if ($coins > 0) {
    $sql = "UPDATE `login-info` SET coins = coins + ? WHERE userid = ?";
    $stmt = $link->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: ' . $link->error);
    }

    $stmt->bind_param("ii", $coins, $userId);
    if ($stmt->execute()) {
        echo "Coins updated successfully. You earned $coins coins this session.";
    } else {
        echo "Execute failed: " . $stmt->error;
    }

    $stmt->close();
}

$link->close();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        body {
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            flex-direction: column; /* Align items vertically */
            background-image: url('<?php echo $backgroundUrl; ?>');
        }

        #timerDisplay {
            font-size: 48px;
            font-weight: bold;
            color: #4a90e2;
            margin-bottom: 20px;
            position: absolute; /* Make the timer display float above the progress bar */
            z-index: 1; /* Ensure it is above the SVG */
        }

        .button-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .custom-button {
        background-color: #1AAD19;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.3s ease;
    }

    #coinsDisplay {
    font-size: 2em;
  }

    .custom-button:hover {
        background-color: #129F12;
    }

        .progress-bar {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 20px;
        }

        .progress-circle {
            stroke: #4a90e2;
            stroke-width: 8;
            fill: transparent;
            transition: stroke-dashoffset 0.5s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div class="progress-bar">
    <svg width="200" height="200">
        <circle id="progress-circle" class="progress-circle" cx="100" cy="100" r="94"></circle>
    </svg>
    <div id="timeLeftDisplay" style="position: absolute; font-size: 32px; color: #333;">00:00:00</div>
</div>
<h1>Focus: <span id="focusNameDisplay"></span></h1>
<div class="button-container">
    <button class="custom-button" onclick="pauseResumeTimer()">Pause/Continue</button>
    <button class="custom-button" onclick="stopTimer()">Stop</button>
</div>
<p id="coinsDisplay">&#x1F4B0;: <span class="coin-emoji"></span><span class="coins-amount"></span></p>

<script>
    let startTime;
    let elapsed;
    let isPaused;
    let timerInterval;
    let addTime = 0;

    const focusName = decodeURIComponent(new URLSearchParams(window.location.search).get('name')) || '默认任务';
    const timerType = new URLSearchParams(window.location.search).get('type');
    const duration = parseInt(new URLSearchParams(window.location.search).get('duration'), 10) * 60000;

    document.getElementById('focusNameDisplay').textContent = focusName;

    // 计算圆圈的周长
    const circumference = 2 * Math.PI * 94;

    // 获取圆圈元素
    const circle = document.getElementById("progress-circle");

    // 设置圆圈的样式
    circle.style.strokeDasharray = circumference;
    circle.style.strokeDashoffset = circumference;

    // Initialize the timer based on session storage data or start anew
    function initTimer() {
        startTime = sessionStorage.getItem('startTime');
        elapsed = sessionStorage.getItem('elapsed');
        isPaused = sessionStorage.getItem('isPaused') === 'true';
        const toPause = sessionStorage.getItem('toPause')
        if (timerType === "down"){
            sessionStorage.removeItem('startTime')
            sessionStorage.removeItem('elapsed')
            sessionStorage.removeItem('isPaused')
            if (toPause === "3"){
                startTime = Date.now();
                elapsed = 0;
                isPaused = false;
            }else if (toPause === "2"){
                startTime = parseInt(startTime);
                elapsed = parseInt(elapsed);
            }

            // Update the display with the current elapsed time
            updateDisplay(elapsed);

            // Start or pause the timer based on session storage data
            if (!isPaused) {
                timerInterval = setInterval(updateTimer, 1000);
            }
        }else{
            if (!isPaused) {
                const params = sessionStorage.getItem('query');
                if (params){
                    let query = JSON.parse(params)
                    addTime = query.addTime??0
                    sessionStorage.removeItem("query")
                }
                addTimer()
                timerInterval = setInterval(addTimer, 1000);
            }

        }
    }

    // Update the timer display with the elapsed time
    function updateDisplay(elapsed) {
        const remaining = duration - elapsed;
        const totalSeconds = Math.max(0, remaining / 1000);  // Prevent negative values
        const seconds = Math.floor(totalSeconds % 60);
        const minutes = Math.floor((totalSeconds / 60) % 60);
        const hours = Math.floor(totalSeconds / 3600);

        // document.getElementById('timerDisplay').textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        document.getElementById('timeLeftDisplay').textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        // Update the progress circle
        const progress = elapsed / duration;
        const offset = circumference * (1 - progress);
        circle.style.strokeDashoffset = offset;
    }

    // Update the timer every second
function updateTimer() {
  const now = Date.now();
  elapsed = now - startTime;
  updateDisplay(elapsed);

  if (timerType === 'down' && elapsed >= duration) {
    clearInterval(timerInterval);
    stopTimer(); // 倒计时结束时调用 stopTimer() 函数进行页面跳转
  }
}

    function addDisplay(addTime)
    {
        const totalSeconds = addTime;
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        const formattedTime = `${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        isPaused = false;
        document.getElementById('timeLeftDisplay').textContent = formattedTime;
    }

    function addTimer()
    {
        addTime = parseInt(addTime)+1
        // 00:00:00
        addDisplay(addTime);
    }

    // Pause or resume the timer
    function pauseResumeTimer() {
        isPaused = !isPaused;
        sessionStorage.setItem('isPaused', isPaused.toString());
        if (isPaused) {
            const params = new URLSearchParams(window.location.search);

            const name = params.get('name'); // 获取name参数的值
            const type = params.get('type'); // 获取type参数的值
            const duration = params.get('duration'); // 获取duration参数的值
            if (sessionStorage.getItem("query")){
                sessionStorage.removeItem('query')
            }
            sessionStorage.setItem('query', JSON.stringify({
                name,type,duration,addTime
            }));
            if (timerType==="down"){
                clearInterval(timerInterval);
                sessionStorage.setItem('startTime', startTime.toString());
                sessionStorage.setItem('elapsed', elapsed.toString());
                sessionStorage.setItem('toPause', "1");
            }
            window.location.href = 'pause.php';
        } else {
           if (timerType==="down"){
               startTime = Date.now() - elapsed;
               sessionStorage.removeItem('isPaused');
               timerInterval = setInterval(updateTimer, 1000);
           }

        }
    }

    // Stop the timer and navigate to the summary page
    function stopTimer() {
        clearInterval(timerInterval);
        sessionStorage.clear();
        const hoursCompleted = Math.floor(elapsed / (1000 * 60 * 60));
        const coins = hoursCompleted * 20;
        const currentDate = new Date().toISOString().split('T')[0];
        window.location.href = `summary.php?name=${encodeURIComponent(focusName)}&date=${currentDate}&elapsed=${elapsed}&coins=${coins}`;
    }

    // Initialize the timer when the window loads
    window.onload = initTimer;
    document.addEventListener('DOMContentLoaded', function() {
        const startTime = Date.now();

        function updateCoins() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000); // 获取已过秒数
            fetch(`update_coins.php?elapsed=${elapsed}`)
                .then(response => response.text())
                .then(data => {
                    const coinsDisplay = document.getElementById('coinsDisplay');
                    coinsDisplay.querySelector('.coin-emoji').textContent = `${data}`;
                })
                .catch(error => console.error('Error updating coins:', error));
        }

        // 每180秒（三分钟）调用一次updateCoins
        setInterval(updateCoins, 1 * 1000);
        updateCoins(); // 页面加载时也调用一次
    });
</script>

</body>
</html>