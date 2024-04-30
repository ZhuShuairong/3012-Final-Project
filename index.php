<?php
session_start();

$userid = "";
$password = "";
$error_message = "";

$userid = $_SESSION['userid'];

$link = mysqli_connect("localhost", "root", "A12345678", "mydata") or die("Cannot open MySQL database connection!<br/>");

$res = mysqli_query($link, "SELECT * FROM myshop");

$sql = "SELECT background FROM `login-info` WHERE userid = '$userid'";
$sql1 = "SELECT inventory FROM `login-info` WHERE userid = '$userid'";

$photoGroups = [
    [9, 10, 6, 4],
    [8, 18, 14, 5],
    [12, 17, 11, 16],
    [7, 14, 13, 6],
    [1, 3, 12, 18],
    [2, 6, 8, 15]
];

$result = mysqli_query($link, $sql1);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $inventoryString = $row['inventory'];
    $inventoryArray = explode(";", $inventoryString);

    
} else {
    echo "Query failed.";
}

$background = "";

foreach ($photoGroups as $index => $group) {
    $allExist = true;
    foreach ($group as $photoId) {
        if (!in_array($photoId, $inventoryArray)) {
            $allExist = false;
            break;
        }
    }
    if ($allExist) {
        $background .= ($index + 1) . ";";
    }
}

$background = rtrim($background, ";");

$updateSql = "UPDATE `login-info` SET background = '$background' WHERE userid = '$userid'";
$updateResult = mysqli_query($link, $updateSql);

?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>Focus Helper</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8f8f8; 
        }
        body {
            background-image: url("index_background.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .table-title {
            text-align: center;
            margin-top: 50px;
            font-size: 36px;
            font-weight: bold;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: #4a90e2;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            padding: 20px;
        }
        .form-style {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7); /* 半透明白色背景 */
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .form-style td {
            padding: 24px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 16px;
            color: #333;
            font-size: 30em;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: calc(100% - 48px);
            padding: 16px;
            border: none;
            background-color: transparent; /* 使输入框透明 */
            font-size: 1.2em; /* 增加输入框字体大小 */
        }
        .form-group button {
            width: 100%;
            padding: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.2em;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .dropdown {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        .dropdown .dropbtn {
            font-size: 1.2em; /* 调整按钮的字体大小来增加大小 */
            padding: 16px 20px; /* 调整按钮的内边距来增加大小 */
            background-color: #505d6f; /* 修改背景颜色为蓝色 */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .dropdown .dropbtn:hover {
            background-color: #76a3ad; /* 修改鼠标悬停时的背景颜色 */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .dropdown-content a:hover {
            background-color: #505d6f;
            color: #333;
        }

        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }
        .dropdown:hover .dropdown-content {
            display: block;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }
        .title {
            text-align: center;
            font-size: 2em;
            margin-top: 0; /* Adjust this if there's too much space */
        }
        .reward-info {
            font-size: 1.2em;
            color: #555;
            padding: 20px;
            margin-top: 20px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center; /* 居中说明文字 */
        }
        #startTimingButton {
            background-color: #505d6f;
            color: #ffffff;
            border: none; /* 去掉边框 */
            border-radius: 5px; /* 设置圆角为 20px，可以根据需要调整 */
            padding: 10px 20px; /* 调整按钮的内边距来增加大小 */
            font-size: 1.0em; /* 调整按钮的字体大小来增加大小 */
        }
        
        #startTimingButton:hover {
            background-color: #76a3ad;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            sessionStorage.setItem('toPause','3')
            $('#timerType').change(function() {
                if ($(this).val() === 'down') {
                    $('#durationContainer').show();
                } else {
                    $('#durationContainer').hide();
                }
            });

            $('#focusForm').submit(function(e) {
                e.preventDefault();
                const focusName = $('#focusName').val();
                const timerType = $('#timerType').val();
                const background = $('#background').val();
                const duration = $('#duration').val() || 0;
                window.location.href = `timer.php?name=${encodeURIComponent(focusName)}&type=${timerType}&background=${background}&duration=${duration}`;
            });
        });
</script>
</head>
<body>
    <div class="dropdown">
        <button class="dropbtn">Menu</button>
        <div class="dropdown-content">
            <a href="shop.php">Shop</a>
            <a href="ranking.php">Ranking</a>
            <a href="forum.php">Forum</a>
            <a href="Unlock.php">Unlock</a>
            <a href="personal.php">Password</a>
            <a href="logout.php">Log out</a>
        </div>
    </div>
    <div class="container">
        <form id="focusForm" class="form-style" action="timer.php" method="GET">
            <table>
                <tr>
                    <td colspan="2">
                        <h1 class="table-title">Traveler</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="focusName">Focus Content:</label>
                    </td>
                    <td>
                        <input type="text" id="focusName" name="focusName" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="timerType">Timing Type:</label>
                    </td>
                    <td>
                        <select id="timerType" name="timerType">
                            <option value="up">Up Timer</option>
                            <option value="down">Down Timer</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="background">Background:</label>
                    </td>
                    <td>
                    <select id="background" name="background">
                        <option value="default">default</option>
                        <?php
                        session_start();

                        $userid = "";
                        $password = "";
                        $error_message = "";
                        $userid = $_SESSION['userid'];
                        $link = mysqli_connect("localhost", "root", "A12345678", "mydata") or die("Cannot open MySQL database connection!<br/>");
                        $res = mysqli_query($link, "SELECT * FROM myshop");
                        $sql = "SELECT background FROM `login-info` WHERE userid = '$userid'";

                        $result = mysqli_query($link, $sql);
                        
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $backgroundString = $row['background'];
                            $background = explode(";", $backgroundString);
                        }
                        
                        $options = array(
                            1 => "Snow mountain",
                            2 => "Mountain",
                            3 => "Forest",
                            4 => "Camp",
                            5 => "Lake",
                            6 => "Grassland"
                        );

                        if (!empty($background)) {
                            foreach ($background as $value) {
                                if (isset($options[$value])) {
                                    echo "<option value='$value'>" . $options[$value] . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </td>
                </tr>
                <tr id="durationContainer" style="display:none;">
                    <td>
                        <label for="duration">Set Duration (minutes):</label>
                    </td>
                    <td>
                        <input type="number" id="duration" name="duration">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" id="startTimingButton">Start Timing</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="reward-info">
                        <p>Earn 20 virtual coins for every full hour timed, whether it's an up timer or a down timer.<br>
                            <br>Note: No coins are awarded if a down timer is stopped before completing an hour.
                            &#x1F4B0; <!-- 添加钱币的 Emoji -->
                        </p>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>


