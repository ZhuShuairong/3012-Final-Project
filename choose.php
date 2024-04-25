<?php
session_start();
$username = "";

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

    // Prepare the SQL statement to retrieve the MIME and name for obtained products
    $sql = "SELECT mime, name, product_id FROM `myshop` WHERE product_id IN ('" . implode("','", $obtained_product_ids) . "')";

    // Execute the SQL statement
    $result = mysqli_query($link, $sql);

    // Check if the query was successful
    if ($result) {
        $selected_product_ids = array(); // Array to store selected product IDs

        // Loop through the results
        while ($row = mysqli_fetch_assoc($result)) {
            $mime = $row['mime'];
            $name = $row['name'];
            $product_id = $row['product_id'];

            // Output the image tag
            echo '<img src="' . $mime . '" alt="' . $name . '" style="width: 100%; height: auto;">';

            // Output the checkbox
            echo '<input type="checkbox" name="selected_images[]" value="' . $product_id . '">';

            // Add the selected product ID to the array
            $selected_product_ids[] = $product_id;
        }

        // Add a submit button to submit the selected checkboxes
        echo '<input type="submit" name="submit" value="提交">';

        // Store the selected product IDs in the $_SESSION
        $_SESSION['selected_product_ids'] = $selected_product_ids;
    } else {
        echo "查询数据库时出错：" . mysqli_error($link);
    }
} else {
    echo "查询数据库时出错：" . mysqli_error($link);
}

// Close the database connection
mysqli_close($link);
?>
<a href="index.php" class="back-button">返回主页</a>