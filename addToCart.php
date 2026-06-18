<?php

// Adds an item to the user's shopping cart
// The cart is stored in the session


include '../includes/DBConn.php';

// Must be logged in to add to cart
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

// Get the item details from the URL
$itemID    = $_GET['id'];
$itemName  = $_GET['name'];
$itemPrice = $_GET['price'];

// Check this item actually exists and has stock
$sql    = "SELECT * FROM tblClothes WHERE itemID = '$itemID' AND status = 'Available' AND quantity > 0";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // Item is out of stock or doesn't exist
    header("Location: ../browse.php");
    exit();
}

$item = mysqli_fetch_assoc($result);

// Start the cart if it doesn't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// If this item is already in the cart, just increase quantity
if (isset($_SESSION['cart'][$itemID])) {
    $_SESSION['cart'][$itemID]['quantity'] = $_SESSION['cart'][$itemID]['quantity'] + 1;
} else {
    // Add the item to the cart for the first time
    $_SESSION['cart'][$itemID] = [
        'name'     => $itemName,
        'price'    => $itemPrice,
        'quantity' => 1,
        'maxQty'   => $item['quantity']  // max stock available
    ];
}

// Go to cart page and show success
header("Location: showCart.php?added=1");
exit();
?>
