<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Design Store</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #e8d7d7;
    }

    .title-bar {
        padding: 20px;
        text-align: center;
        font-size: 24px;
        background-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 1000px; /* Adjust the value as needed */
        margin: 40px auto 20px; /* Center the title bar horizontally */
    }

    .title-bar h1 {
    text-align: center;
    margin-top: 20px;
    font-size: 24px;
    }   

    .title-bar form {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    .photo-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .photo-group {
        flex: 0 0 100%;
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
        gap: 30px;
    }

    .photo-item {
        font-size: 16px;
        flex: 0 0 calc(20% - 40px);
        padding: 20px;
        box-sizing: border-box;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 150px;
        height: 150px;
        text-align: center; /* 字体居中 */
    }

    .photo-item:before {
        content: "";
        display: block;
        width: 3cm;
        height: 3cm;
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        max-width: 100%;
        max-height: 100%;
    }

    .photo-item span {
        font-size: 44px;
    }

    .photo-item button {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 2500px;
    height: 300px;
    border: none;
    background: none;
    cursor: pointer;
    }

    .photo-item.background {
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 8px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
        background-size: cover;
        background-position: center;
    }

    .photo-item.background:hover {
        transform: translateY(-2px) scale(1.1);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .back-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #ccc;
        color: #000;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 20px;
    }
</style>
    <script>
      // 定义一个数组来存储被点击的背景ID

      var clickedBgIds = [];
function toggleGrayscale(button, bgMime, bgName, bgId) {
  // 检查当前按钮是否已被点击
  var isClicked = clickedBgIds.includes(bgId);

  // 如果按钮已被点击，则将图像恢复为原色并从数组中移除背景ID
  if (isClicked) {
    button.style.filter = 'none'; // 恢复原色
    var index = clickedBgIds.indexOf(bgId);
    clickedBgIds.splice(index, 1); // 从数组中移除背景ID
  } else {
    // 如果按钮未被点击，则将图像转为黑白并将背景ID添加到数组中
    button.style.filter = 'grayscale(100%)'; // 转为黑白
    clickedBgIds.push(bgId); // 将背景ID添加到数组中
  }
  // 在控制台输出已被点击的背景ID数组
  console.log(clickedBgIds);
}
</script>
</head>
<body>

<div class="title-bar">
<h1>Choose your background:</h1>
</div>

<?php
session_start();
$userid = "";
$password = "";
$error_message = "";

$userid = $_SESSION['userid'];

$link = mysqli_connect("localhost", "root", "A12345678", "mydata") or die("Cannot open MySQL database connection!<br/>");

$res = mysqli_query($link, "SELECT * FROM myshop");
$res1 = mysqli_query($link, "SELECT * FROM background");

$sql1 = "SELECT inventory FROM `login-info` WHERE userid = '$userid'";


$result = mysqli_query($link, $sql1);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $inventoryString = $row['inventory'];
    $inventoryArray = explode(";", $inventoryString);

    if (count($inventoryArray) > 0 && isset($inventoryArray[0])) {
       
    } else {
        echo "Inventory array is empty.";
    }
} else {
    echo "Query failed.";
}

// Obtain form data
if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
} else {
    //检测
    print "no";
}

if (isset($_SESSION["password"])) {
    $password = $_SESSION["password"];
}

// Check if the user filled in the userid and password
if ($userid != "" && $password != "") {
    // Connect to the database
    mysqli_query($link, 'SET NAMES utf8');
    mysqli_query($link, 'SET NAMES utf8');

    // Define SQL string
    $sql = "SELECT * FROM user WHERE userid='" . $userid . "' AND password='" . $password . "'";

    // Execute SQL command
    $result = mysqli_query($link, $sql);
    $result1 = mysqli_query($link, $sql);

    $total_records = mysqli_num_rows($result);
    $total_records1 = mysqli_num_rows($result1);

    // Check if login data matched with the database
    if ($total_records > 0) {
        // If matched, specify session variable login_session as true
        $_SESSION["login_session"] = true;
        $_SESSION["userid"] = $userid; // 设置会话变量userid
        header("Location: index.php");
    } else { // Login fails
        $error_message = "userid (phone number) or password is wrong!";
        $_SESSION["login_session"] = false;
    }
}

$photoGroups = [
    [9, 10, 14, 4],
    [8, 18, 14, 5],
    [12, 17, 14, 16],
    [7, 11, 13, 6],
    [3, 6, 8, 15],
    [3, 6, 8, 15]
];

echo "<div class='photo-container'>";

foreach ($photoGroups as $groupIndex => $group) {
    echo "<div class='photo-group'>";

    $numPhotos = count($group);
    $lastPhotoIndex = $numPhotos - 1;

    $allColorPhotos = true; // 跟踪所有照片是否都为彩色

    foreach ($group as $photoIndex => $photoId) {
        $sql = "SELECT mime, name FROM `myshop` WHERE product_id = $photoId";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $mime = $row['mime'];
        $name = $row['name'];

        // Determine if the photo is color or black and white based on inventory array
        $isColor = in_array($photoId, $inventoryArray);

        // Check if the photo is black and white
        if (!$isColor) {
            $allColorPhotos = false; // 如果有一张照片不是彩色，则将变量设为 false
        }

        // Display the photo as a button
        echo "<div class='photo-item'>";
        echo "<img src='3012 final picture/$mime' alt='$name' style='filter: " . ($isColor ? 'none' : 'grayscale(100%)') . ";'>";

        // Display plus sign (+) or equal sign (=) between photos
        if ($photoIndex !== $lastPhotoIndex) {
            echo "<span>+</span>";
        } else {
            echo "<span>=</span>";
        }

        echo "</div>";

        mysqli_free_result($result);
    }

    // Retrieve the corresponding background image from the 'background' table
    $bg_id = $groupIndex + 1;
    $bgSql = "SELECT mime, name FROM `background` WHERE bg_id = $bg_id";
    $bgResult = mysqli_query($link, $bgSql);
    $bgRow = mysqli_fetch_assoc($bgResult);

    $bgMime = $bgRow['mime'];
    $bgName = $bgRow['name'];

    mysqli_free_result($bgResult);

  // 检查会话中是否已存在 clickedBgIds 数组
if (!isset($_SESSION['clickedBgIds'])) {
    // 如果不存在，则创建一个空数组并存入会话
    $_SESSION['clickedBgIds'] = [];
}

// Check if all photos in the group are color photos
if ($allColorPhotos) {
    // Display the background image as a button
    echo "<div class='photo-item background' style='background-image: url('3012 final picture/$bgMime'); background-size: 300px 200px;'>";
    echo "<button onclick=\"toggleGrayscale(this, '$bgMime', '$bgName', $bg_id);\">";
    echo "<img src='3012 final picture/$bgMime' alt='$bgName'>"; // 设置背景图像尺寸
    echo "</button>";
    echo "</div>";

    // 将 bg_id 添加到 clickedBgIds 数组中
    $_SESSION['clickedBgIds'][] = $bg_id;
} else {
    // Display a disabled button indicating that not all photos are color photos
    echo "<div class='photo-item background'>";
    echo "<img src='$bgMime' alt='$bgName'>";
    echo "</div>";
}
    echo "</div>";
}



echo "</div>";
?>
<a href="index.php" class="back-button">Back to Main Page</a>
</body>
</html>
