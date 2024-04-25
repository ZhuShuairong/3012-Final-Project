<?php
session_start();
$username = "";
$password = "";
$error_message = "";
$link = mysqli_connect("localhost", "root", "A12345678", "mydata")
    or die("Cannot open MySQL database connection!<br/>");

// Get the username from the session or from wherever it is stored
$username = $_SESSION['username'];

// Prepare the SQL statement to retrieve the inventory from the database
$sql = "SELECT inventory FROM `login-info` WHERE username = '$username'";

// Execute the SQL statement
$result = mysqli_query($link, $sql);

// Check if the query was successful
if ($result) {
    // Fetch the result row
    $row = mysqli_fetch_assoc($result);

    // Get the inventory value
    $inventory_string = $row['inventory'];

    // Convert the inventory string to an array of product IDs
    $obtained_product_ids = explode(";", $inventory_string);

    // Assuming you have established a database connection
    // and have assigned the connection object to $conn

    // Prepare the SQL statement to retrieve the MIME and name for obtained products
    $sql = "SELECT mime, name FROM `myshop` WHERE product_id IN ('" . implode("','", $obtained_product_ids) . "')";

    // Execute the SQL statement
    $result = mysqli_query($link, $sql);

    // Check if the query was successful
    if ($result) {
        // Loop through the results
        while ($row = mysqli_fetch_assoc($result)) {
            $mime = $row['mime'];
            $name = $row['name'];

            // Output the image tag
            echo '<img src="' . $mime . '" alt="' . $name . '" style="width: 100%; height: auto;">';
        }
    } else {
        echo "查询数据库时出错：" . mysqli_error($conn);
    }
} else {
    echo "查询数据库时出错：" . mysqli_error($conn);
}
?>