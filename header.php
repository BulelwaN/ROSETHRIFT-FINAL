<?php

// Include this at the top of every page

// Count how many items are in the cart
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}

// Count wishlist items (from the database)
$wishlistCount = 0;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($conn)) {
    $uid           = $_SESSION['user_id'];
    $wRes          = mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblWishlist WHERE userID = '$uid'");
    $wRow          = mysqli_fetch_assoc($wRes);
    $wishlistCount = $wRow['c'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . " - " : ""; ?>Rosethrift</title>
    <link rel="stylesheet" href="<?php echo isset($cssPath) ? $cssPath : ""; ?>css/style.css">
</head>
<body>

<!-- ===== TOP NAVIGATION BAR ===== -->
<header>
    <div class="header-box">

        <!-- Website Logo / Name -->
        <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>index.php" class="logo">
            🌹 Rosethrift
        </a>

        <!-- Navigation Links -->
        <nav>
            <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>index.php">Home</a>
            <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>browse.php">Shop</a>

            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true): ?>
                <!-- Links for logged-in users -->
                <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>wishlist/viewWishlist.php">
                    ♥ Wishlist
                    <?php if ($wishlistCount > 0): ?>
                        <span class="cart-badge"><?php echo $wishlistCount; ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>cart/showCart.php">
                    🛒 Cart
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>cart/purchaseHistory.php">My Orders</a>

                <?php if ($_SESSION['user_type'] == 'seller'): ?>
                    <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>seller/myListings.php">My Listings</a>
                    <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>seller/sellRequest.php">Sell Item</a>
                <?php endif; ?>

                <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>contact.php">Messages</a>
                <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>logout.php" class="btn-logout">
                    Logout (<?php echo $_SESSION['user_name']; ?>)
                </a>

            <?php else: ?>
                <!-- Links for guests (not logged in) -->
                <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>login.php">Login</a>
                <a href="<?php echo isset($rootPath) ? $rootPath : ""; ?>register.php">Register</a>
            <?php endif; ?>
        </nav>

    </div>
</header>
