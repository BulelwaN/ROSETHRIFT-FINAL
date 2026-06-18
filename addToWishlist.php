<?php

// Saves an item to the user's wishlist


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

// If no valid item ID, go back
if ($itemID == 0) {
    header("Location: ../browse.php");
    exit();
}

// Check the item actually exists
$checkSQL = "SELECT itemID FROM tblClothes WHERE itemID = '$itemID'";
$check    = mysqli_query($conn, $checkSQL);

if (mysqli_num_rows($check) == 0) {
    header("Location: ../browse.php");
    exit();
}

// Check if it is already in the wishlist
$existSQL    = "SELECT wishlistID FROM tblWishlist WHERE userID = '$userID' AND itemID = '$itemID'";
$existResult = mysqli_query($conn, $existSQL);

if (mysqli_num_rows($existResult) == 0) {
    // Not yet saved — add it now
    $insertSQL = "INSERT INTO tblWishlist (userID, itemID) VALUES ('$userID', '$itemID')";
    mysqli_query($conn, $insertSQL);
}

// Go back to the item detail page
header("Location: ../viewItem.php?id=$itemID&wishlisted=1");
exit();
?>
