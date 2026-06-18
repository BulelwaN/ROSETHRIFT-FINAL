<?php

// Removes one item from the cart


include '../includes/DBConn.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Get the item ID to remove
$itemID = $_GET['id'];

// Remove it from the session cart
unset($_SESSION['cart'][$itemID]);

// Go back to cart
header("Location: showCart.php");
exit();
?>
