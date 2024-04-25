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
        $selected_count = 0; // Counter for selected checkboxes
        // Loop through the results
        while ($row = mysqli_fetch_assoc($result)) {
            $mime = $row['mime'];
            $name = $row['name'];
            $product_id = $row['product_id'];

            // Output the image tag
            echo '<img src="' . $mime . '" alt="' . $name . '" style="width: 100%; height: auto;">';

            // Check if the maximum number of checkboxes has been reached
            if ($selected_count < 3) {
                // Output the checkbox
                echo '<input type="checkbox" name="selected_images[]" value="' . $product_id . '">';
                $selected_count++;
            }
        }
        
        // Display a message if the maximum number of checkboxes has been reached
        if ($selected_count >= 3) {
            echo '<p>最多只能选择三个照片。</p>';
        }
        
        // Add a submit button to submit the selected checkboxes
        echo '<input type="submit" name="submit" value="提交">';
    } else {
        echo "查询数据库时出错：" . mysqli_error($link);
    }
} else {
    echo "查询数据库时出错：" . mysqli_error($link);
}

// Close the database connection
mysqli_close($link);
?>
<a href="index.php" class="back-button">Back to Main Page</a>