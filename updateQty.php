<?php

// updateQty.php - Update Cart Item Quantity
// Called when user changes quantity in cart

include '../includes/DBConn.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemID = $_POST['itemID'];
    $newQty = (int)$_POST['newQty'];

    if ($newQty <= 0) {
        // Remove item if quantity is 0 or less
        unset($_SESSION['cart'][$itemID]);
    } else {
        // Update the quantity
        $_SESSION['cart'][$itemID]['quantity'] = $newQty;
    }
}

header("Location: showCart.php");
exit();
?>
