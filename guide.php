<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Design Store</title>
    <style>
        .photo-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 10px;
        }

        .photo-group {
            flex: 0 0 100%;
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
            gap: 10px;
        }

        .photo-item {
            flex: 0 0 calc(20% - 10px);
            padding: 5px;
            box-sizing: border-box;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
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
        }

        .photo-item span {
            font-size: 24px;
        }

        .photo-item button {
            width: 100%;
            height: 100%;
            padding: 0;
            border: none;
            background: none;
            cursor: pointer;
        }

        /* Update styles for the background image buttons */
        .photo-item.background {
            border: 2px solid #000;
            border-radius: 8px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .photo-item.background:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<?php
session_start();
$userid = "";
$password = "";
$error_message = "";

$link = mysqli_connect("localhost", "root", "A12345678", "mydata") or die("Cannot open MySQL database connection!<br/>");
$link1 = mysqli_connect("localhost", "root", "A12345678", "mydata") or die("Cannot open MySQL database connection!<br/>");

$res = mysqli_query($link, "SELECT * FROM myshop");
$res1 = mysqli_query($link1, "SELECT * FROM background");

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
    mysqli_query($link1, 'SET NAMES utf8');

    // Define SQL string
    $sql = "SELECT * FROM user WHERE userid='" . $userid . "' AND password='" . $password . "'";
    $sql1 = "SELECT inventory FROM `login-info` WHERE userid = '$userid'";

    $result = mysqli_query($link, $sql1);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $inventoryString = $row['inventory'];
        $inventoryArray = explode(";", $inventoryString);
    } else {
        print "no";
    }

    // Execute SQL command
    $result = mysqli_query($link, $sql);
    $result1 = mysqli_query($link1, $sql);

    $total_records = mysqli_num_rows($result);
    $total_records1 = mysqli_num_rows($result1);

    // Check if login data matched with the database
    if ($total_records > 0) {
        // If matched, specify session variable login_session as true
        $_SESSION["login_session"] = true;
        $_SESSION["userid"] = $userid; // 设置会话变量userid
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

    foreach ($group as $photoIndex => $photoId) {
        $sql = "SELECT mime, name FROM `myshop` WHERE product_id = $photoId";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $mime = $row['mime'];
        $name = $row['name'];

        // Check if photoIndex is not in inventoryArray
        if (!in_array($photoIndex, $inventoryArray)) {
            // Convert the photo to black and white
            $sql = "SELECT image_data FROM myshop WHERE product_id = $photoId";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($result);
            $imageData = $row['image_data'];

            // Convert the image to black and white
            $image = imagecreatefromstring($imageData);
            imagefilter($image, IMG_FILTER_GRAYSCALE);

            // Save the converted image to a temporary file
            $tmpFilename = tempnam(sys_get_temp_dir(), 'bw_');
            imagejpeg($image, $tmpFilename);

            // Display the converted image
            echo "<div class='photo-item'>";
            echo "<button><img src='$tmpFilename' alt='$name'></button>";
            echo "</div>";

            // Clean up the temporary file and resources
            imagedestroy($image);
            unlink($tmpFilename);

            mysqli_free_result($result);
        } else {
            // Display the photo as a button
            echo "<div class='photo-item'>";
            echo "<button><img src='3012 final picture/$mime' alt='$name'></button>";
            echo "</div>";
        }

        mysqli_free_result($result);
    }


    // Retrieve the corresponding background image from the 'background' table
    $bg_id = $groupIndex + 1;
    $bgSql = "SELECT mime, name FROM `background` WHERE bg_id = $bg_id";
    $bgResult = mysqli_query($link1, $bgSql);
    $bgRow = mysqli_fetch_assoc($bgResult);

    $bgMime = $bgRow['mime'];
    $bgName = $bgRow['name'];

    mysqli_free_result($bgResult);

    // Display the background image as a raised button with a link to time.php
    echo "<div class='photo-item background'>";
    echo "<img src='$bgMime' alt='$bgName'>";
    echo "</div>";

    echo "</div>";
}

echo "</div>";
?>
</body>
</html>`