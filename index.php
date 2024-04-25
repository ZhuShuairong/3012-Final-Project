<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>专注助手主页</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* 下拉菜单的基本样式 */
        .dropdown {
            position: fixed;
            top: 20px;
            right: 20px;
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
            text-align: left;
        }

        .dropdown-content a:hover {background-color: #f1f1f1}
        .dropdown:hover .dropdown-content {display: block;}
        .dropdown:hover .dropbtn {background-color: #3e8e41;}

        /* 容器和表单样式 */
        body, html {height: 100%; margin: 0; font-family: Arial, sans-serif;}
        .container {display: flex; justify-content: center; align-items: center; height: 100%;}
        .form-style {padding: 20px; background: #f4f4f4; border: 1px solid #ccc; border-radius: 5px;}
        .form-group {margin-bottom: 15px;}
        .form-group label {display: block; margin-bottom: 5px;}
        .form-group input[type="text"], .form-group input[type="number"], .form-group select {width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;}
        .form-group button {padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;}
        .form-group button:hover {background-color: #45a049;}
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
                const duration = $('#duration').val() || 0; // 提供默认值为0
                window.location.href = `timer.php?name=${encodeURIComponent(focusName)}&type=${timerType}&duration=${duration}`;
            });

        });
    </script>
</head>
<body>
    <h1>欢迎使用专注助手</h1>
    <div class="dropdown">
        <button class="dropbtn">Menu</button>
        <div class="dropdown-content">
            <a href="shop.php">Shop</a>
            <a href="records.php">Record</a>
            <a href="block.php">Forum</a>
            <a href="profile.php">Personal</a>
        </div>
    </div>

    <div class="container">
        <form id="focusForm" class="form-style">
            <div class="form-group">
                <label for="focusName">Content:</label>
                <input type="text" id="focusName" name="focusName" required>
            </div>
            <div class="form-group">
                <label for="timerType">Timing type:</label>
                <select id="timerType" name="timerType">
                    <option value="up">正计时</option>
                    <option value="down">倒计时</option>
                </select>
            </div>
            <div class="form-group" id="durationContainer" style="display:none;">
                <label for="duration">设置时长（分钟）:</label>
                <input type="number" id="duration" name="duration">
            </div>
            <div class="form-group">
                <button type="submit">开始计时</button>
            </div>
        </form>
    </div>
</body>
</html>

