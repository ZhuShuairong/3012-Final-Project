<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
function updateBalance() {
    $.ajax({
        url: 'get_balance.php', // 替换为获取balance的服务器端脚本的URL
        type: 'GET',
        success: function(response) {
            $('#balance').text(response); // 更新balance的值
        },
        error: function() {
            console.log('Failed to retrieve balance.');
        }
    });
}

$(document).ready(function() {
    updateBalance();
});

updateBalance();
</script>
<?php
session_start();
$userid = "";
$password = "";
$error_message = "";
$link = mysqli_connect("localhost", "root", "A12345678", "mydata")
    or die("Cannot open MySQL database connection!<br/>");

$res = mysqli_query($link, "SELECT * FROM myshop");

// Obtain form data
if (isset($_POST["userid"])) {
    $userid = $_POST["userid"];
}
if (isset($_POST["password"])) {
    $password = $_POST["password"];
}

// Check if the user filled in the userid and password
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
        header("Location: index.php");
    } else { // Login fails
        $error_message = "userid (phone number) or password is wrong!";
        $_SESSION["login_session"] = false;
    }
    
    mysqli_close($link);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // 根据$product_id获取商品价格
    $sql = "SELECT price FROM myshop WHERE product_id = '$product_id'";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $price = $row['price'];

        // 获取当前用户的coins数量
        $sql = "SELECT coins FROM `login-info` WHERE userid = '1'";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $currentCoins = $row['coins'];

            // 检查用户的coins是否足够购买商品
            if ($currentCoins >= $price) {
                // 更新用户的coins数量
                $newCoins = $currentCoins - $price;
                $sql = "UPDATE `login-info` SET coins = '$newCoins' WHERE userid = '1'";
                $updateResult = mysqli_query($link, $sql);

                if ($updateResult) {
                    echo "Purchase successful. Your new balance is: " . $newCoins;
                } else {
                    echo "Failed to update coins.";
                }
            } else {
                echo "Insufficient coins. Unable to make the purchase.";
            }
        } else {
            echo "No records found for the user.";
        }
    } else {
        echo "Invalid product ID.";
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

// Handle adding items to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $cart_item = array(
        'product_id' => $product_id,
        'quantity' => 1
    );

    // Check if cart session variable exists, if not, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the item is already in the cart, if yes, update the quantity
    $found = false;
    if (!$found) {
        print "You have bought the item!";
    }


    // If the item is not already in the cart, add it
    if (!$found) {
        $_SESSION['cart'][] = $cart_item;
    }

    header("Location: shop.php");
    exit();
}

// Handle displaying cart items
if (isset($_GET['action']) && $_GET['action'] === 'cart') {
    // Check if cart session variable exists, if not, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $cart_items = $_SESSION['cart'];
}

// Close the database connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Design Store</title>
    <style>
        .container {
            display: flex;
        }

        .search-bar {
            flex: 1;
            padding: 10px;
        }

        .item-list {
            flex: 3;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
        }

        .item-card {
            width: 200px;
            margin: 10px;
        }

        .back-button {
            position: fixed;
            bottom: 10px;
            left: 10px;
            padding: 10px;
            background-color: #ccc;
            color: #000;
            text-decoration: none;
        }

        .cart-button {
            position: fixed;
            bottom: 10px;
            right: 10px;
            padding: 10px;
            background-color: #ccc;
            color: #000;
            text-decoration: none;
        }

        .cart-item {
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="search-bar">
            <p>Balance: <span id="balance"></span></p>
            <h1>Welcome to the Shop</h1>
            <form action="" method="POST">
                <input type="text" name="keyword" placeholder="Search">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="item-list">
            <?php if (count($myshop) > 0): ?>
                <?php foreach ($myshop as $row): ?>
                    
                    <div class="item-card">
                        <a href="product.php?product_id=<?php echo $row['product_id']; ?>">
                            <img src="<?php echo $row['mime']; ?>" alt="<?php echo $row['name']; ?>" style="width: 100%; height: auto;">
                        </a>
                        <h4><?php echo $row['name']; ?></h4>
                        <p>Price: $<?php echo $row['price']; ?></p>
                        
                        <form action="" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" name="add_to_cart">Buy</button>
                        </form>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No items available.</p>
            <?php endif; ?>
        </div>
    </div>
    <a href="cart.php?action=cart" class="cart-button">View Cart</a>
    <a href="index.php" class="back-button">Back to Main Page</a>

    <?php
// Handle adding items to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $cart_item = array(
        'product_id' => $product_id,
        'quantity' => 1
    );

    // Check if cart session variable exists, if not, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the item is already in the cart, if yes, update the quantity
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] === $product_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    // If the item is not already in the cart, add it
    if (!$found) {
        $_SESSION['cart'][] = $cart_item;
    }

    header("Location: index.php");
    exit();
}

// Handle displaying cart items
if (isset($_GET['action']) && $_GET['action'] === 'cart') {
    // Check if cart session variable exists, if not, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $cart_items = $_SESSION['cart'];
}
    ?>

</body>
</html>