<?php
session_start();
$userid = "";
$password = "";
$error_message = "";
$link = mysqli_connect("localhost", "root", "A12345678", "mydata")
    or die("Cannot open MySQL database connection!<br/>");

$res = mysqli_query($link, "SELECT * FROM myshop");

$sql1 = "SELECT inventory FROM `login-info` WHERE userid = '$userid'";


$result = mysqli_query($link, $sql1);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $inventoryString = $row['inventory'];

        if (!empty($inventoryString)) {
            $inventoryArray = explode(";", trim($inventoryString));

            if (count($inventoryArray) > 0 && isset($inventoryArray[0])) {
                // 执行您希望在库存数组不为空时执行的操作
            } else {
                echo "Inventory array is empty.";
            }
        } else {
            echo "Inventory string is empty.";
        }
    } else {
        
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

// Check if the user filled in the userid and password+
if ($userid != "" && $password != "") {
    // Connect to the database
    mysqli_query($link, 'SET NAMES utf8');

    // Define SQL string
    $sql = "SELECT * FROM user WHERE (userid='" . $userid . "') AND password='" . $password . "'";

    // Execute SQL command
    $result = mysqli_query($link, $sql);
    $total_records = mysqli_num_rows($result);

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

    mysqli_close($link);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // 根据 $product_id 获取商品价格
    $sql = "SELECT price FROM myshop WHERE product_id = '$product_id'";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $price = $row['price'];

        // 获取当前用户的 coins 数量
        $sql = "SELECT coins, inventory FROM `login-info` WHERE userid = '$userid'";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $currentCoins = $row['coins'];
            $inventoryString = $row['inventory'];
            $inventoryArray = explode(';', $inventoryString);

            // 检查用户的 coins 是否足够购买商品
            if ($currentCoins >= $price) {
                // 检查购物车中是否已经包含该商品
                $foundInCart = in_array($product_id, $inventoryArray);

                if ($foundInCart) {
                    echo "<script>alert('The item already exists in the shopping cart. Cannot purchase again.');</script>";
                } else {
                    // 更新用户的 coins 数量
                    $newCoins = $currentCoins - $price;
                    $sql = "UPDATE `login-info` SET coins = '$newCoins' WHERE userid = '$userid'";
                    $updateResult = mysqli_query($link, $sql);

                    if ($updateResult) {
                        // 更新成功

                        // 将选定的商品添加到购物车
                        $inventoryArray[] = $product_id;
                        $updatedInventoryString = implode(';', $inventoryArray);
                        $sql = "UPDATE `login-info` SET inventory = '$updatedInventoryString' WHERE userid = '$userid'";
                        $updateInventoryResult = mysqli_query($link, $sql);

                        if ($updateInventoryResult) {
                            echo "<script>alert('Item added to the shopping cart.');</script>";
                        } else {
                            echo "<script>alert('Failed to update the shopping cart.');</script>";
                        }
                    } else {
                        // 更新失败
                        echo "<script>alert('Failed to update coins.');</script>";
                    }
                }
            } else {
                echo "<script>alert('Insufficient coins. Unable to make the purchase.');</script>";
            }
        } else {
            echo "<script>alert('No records found for the user.');</script>";
        }
    } else {
        echo "<script>alert('Invalid product ID.');</script>";
    }
}

// Search keyword
if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $res = mysqli_query($link, "SELECT * FROM myshop WHERE name LIKE '%$keyword%'");
}

// Get myshop data and assign it to the $myshop array
$myshop = array();
while ($row = mysqli_fetch_assoc($res)) {
    $myshop[] = $row;
}


?>

<!DOCTYPE html>
<html lang="en">

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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-bar {
            padding: 20px;
            text-align: center;
            font-size: 24px;
            background-color: rgba(255, 255, 255, 0.8);;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-bar h1 {
            font-size: 50px;
            margin-bottom: 20px;
        }

        .search-bar form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            font-size: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
        }

        .search-bar button {
            padding: 10px 20px;
            font-size: 20px;
            background-color: #D3BBB8;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .item-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-content: flex-start;
            margin-top: 20px;
        }

        .item-card {
            width: 20%;
            margin: 10px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }

        .item-card:hover {
            transform: translateY(-5px);
        }

        .item-card img {
            width: 200px;
            height: 200px;
            object-fit: contain;
            cursor: pointer;
        }

        .item-card h4 {
            margin-top: 10px;
            font-size: 18px;
        }

        .item-card p {
            margin-top: 5px;
            font-size: 16px;
        }

        .item-card button {
            padding: 12px 24px;
            font-size: 18px;
            background-color: #D3BBB8;
            color: #000000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .back-button,
        .cart-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ccc;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
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

        .cart-item {
            margin: 10px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-block;
            border-radius: 4px;
        }

        #balance {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* 响应式样式 */
        @media (max-width: 768px) {
            .item-card {
                width: 30%;
            }
        }

        @media (max-width: 480px) {
            .item-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="search-bar">
        <div id="balance">&#x1F4B0<span id="balance-value"></span></div>

            <script>
    
                
                // Creating XMLHttpRequest object
                var xhr = new XMLHttpRequest();

                // Defining the request URL
                var url = "get_balance.php";

                // Sending the AJAX request
                xhr.open("GET", url, true);
                xhr.send();

                // Listening to the AJAX request state change
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        // Request completed
                        if (xhr.status === 200) {
                            // Request successful
                            var balance = xhr.responseText;
                            document.getElementById("balance-value").innerText = balance;
                        } else {
                            // Request failed
                            console.error("Failed to get balance.");
                        }
                    }
                };
            </script>
            <h1>Welcome to the Shop</h1>
            <form action="shop.php" method="POST">
                <input type="text" name="keyword" placeholder="Search">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="item-list">
            <?php if (count($myshop) > 0) : ?>
                <?php $itemCount = 0; ?>
                <?php foreach ($myshop as $row) : ?>
                    <?php if ($itemCount % 5 === 0) : ?>
                        <div style="clear:both;"></div>
                    <?php endif; ?>
                    <div class="item-card">
                        <img src="<?php echo "3012 final picture/" . $row['mime']; ?>" alt="<?php echo $row['name']; ?>">
                        <h4><?php echo $row['name']; ?></h4>
                        <p>Price: $<?php echo $row['price']; ?></p>
                        <form action="" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" name="add_to_cart">Purchase</button>
                        </form>
                    </div>
                    <?php $itemCount++; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No items available.</p>
            <?php endif; ?>
        </div>
    </div>
    <a href="index.php" class="back-button">Back to Main Page</a>

   
</body>
</html>