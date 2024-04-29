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
    [9, 10, 14, 4],
    [8, 18, 14, 5],
    [12, 17, 14, 16],
    [7, 11, 13, 6],
    [3, 6, 8, 15],
    [3, 6, 8, 15]
];

$result = mysqli_query($link, $sql1);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $inventoryString = $row['inventory'];
    $inventoryArray = explode(";", $inventoryString);

    if (count($inventoryArray) > 0 && isset($inventoryArray[0])) {
        echo "Inventory array";
    } else {
        echo "Inventory array is empty.";
    }
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
if ($updateResult) {
    echo "Background updated.";
} else {
    echo "Failed to update background.";
}
?>