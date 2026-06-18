<?php

// emptyCart.php - EmptyCart() Function
// Removes ALL items from the cart


include '../includes/DBConn.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Clear the entire cart
$_SESSION['cart'] = [];

header("Location: showCart.php");
exit();
?>
