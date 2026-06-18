<?php

// wishlist/removeFromWishlist.php - RemoveFromWishlist()
// Deletes an item from the user's wishlist


$cssPath  = "../";
$rootPath = "../";

include '../includes/DBConn.php';

// Must be logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$itemID = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
$from   = isset($_GET['from']) ? $_GET['from'] : ""; // used to know where to send user back

if ($itemID == 0) {
    header("Location: viewWishlist.php");
    exit();
}

// Delete this item from the user's wishlist
$deleteSQL = "DELETE FROM tblWishlist WHERE userID = '$userID' AND itemID = '$itemID'";
mysqli_query($conn, $deleteSQL);

// If the user came from the item detail page, go back there
if ($from == "item") {
    header("Location: ../viewItem.php?id=$itemID&removed=1");
} else {
    header("Location: viewWishlist.php?removed=1");
}
exit();
?>
