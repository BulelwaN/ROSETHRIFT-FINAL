<?php

// viewItem.php - Item Detail Page
// Shows full info about a single clothing item

$pageTitle = "View Item";
$cssPath   = "";
$rootPath  = "";

include 'includes/DBConn.php';
include 'includes/header.php';

// Get the item ID from the URL
$itemID = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// If no valid ID, go back to browse page
if ($itemID == 0) {
    header("Location: browse.php");
    exit();
}

// Get the item details from the database
$sql    = "SELECT tblClothes.*, tblUser.username AS sellerName, tblUser.name AS sellerFullName
           FROM tblClothes
           JOIN tblUser ON tblClothes.sellerID = tblUser.userID
           WHERE tblClothes.itemID = '$itemID'";
$result = mysqli_query($conn, $sql);

// If item not found, show an error
if (mysqli_num_rows($result) == 0) {
    echo '<div class="container"><div class="alert alert-error">Item not found. <a href="browse.php">Back to Shop</a></div></div>';
    include 'includes/footer.php';
    exit();
}

$item = mysqli_fetch_assoc($result);

// Check if this item is already in the user's wishlist
$inWishlist = false;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    $userID      = $_SESSION['user_id'];
    $wishSQL     = "SELECT wishlistID FROM tblWishlist WHERE userID = '$userID' AND itemID = '$itemID'";
    $wishResult  = mysqli_query($conn, $wishSQL);
    $inWishlist  = (mysqli_num_rows($wishResult) > 0);
}

// Work out the image to show
$imgSrc = ($item['image'] != "") ? $item['image'] : "images/shirt.jpeg";

// Set a badge colour based on condition
$condBadge = "badge-blue";
if ($item['conditionItem'] == 'New')  $condBadge = "badge-green";
if ($item['conditionItem'] == 'Fair') $condBadge = "badge-yellow";
?>

<div class="container">

    <!-- Back link -->
    <p style="margin-bottom:20px;">
        <a href="browse.php" style="color:#c0392b; text-decoration:none;">← Back to Shop</a>
    </p>

    <!-- ===== ITEM DETAIL CARD ===== -->
    <div class="card" style="display:flex; flex-wrap:wrap; gap:30px;">

        <!-- LEFT: Large Product Image -->
        <div style="flex:1; min-width:280px; max-width:420px;">
            <img src="<?php echo $imgSrc; ?>"
                 alt="<?php echo $item['itemName']; ?>"
                 style="width:100%; border-radius:10px; object-fit:cover; max-height:420px;">
        </div>

        <!-- RIGHT: Item Info -->
        <div style="flex:1; min-width:260px;">

            <h2 style="font-size:1.8rem; color:#333; margin-bottom:8px;">
                <?php echo $item['itemName']; ?>
            </h2>

            <!-- Price -->
            <div class="item-price" style="font-size:2rem; margin-bottom:14px;">
                R<?php echo number_format($item['price'], 2); ?>
            </div>

            <!-- Details list -->
            <table style="width:100%; border:none; margin-bottom:20px;">
                <tr>
                    <td style="padding:6px 0; color:#777; width:110px; border:none;">Brand:</td>
                    <td style="padding:6px 0; border:none; font-weight:600;"><?php echo $item['brand'] ? $item['brand'] : "Unknown"; ?></td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#777; border:none;">Category:</td>
                    <td style="padding:6px 0; border:none;"><?php echo $item['category']; ?></td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#777; border:none;">Size:</td>
                    <td style="padding:6px 0; border:none;"><?php echo $item['size']; ?></td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#777; border:none;">Colour:</td>
                    <td style="padding:6px 0; border:none;"><?php echo $item['colour']; ?></td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#777; border:none;">Condition:</td>
                    <td style="padding:6px 0; border:none;">
                        <span class="badge <?php echo $condBadge; ?>"><?php echo $item['conditionItem']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#777; border:none;">In Stock:</td>
                    <td style="padding:6px 0; border:none;">
                        <?php if ($item['quantity'] > 0): ?>
                            <span class="badge badge-green"><?php echo $item['quantity']; ?> available</span>
                        <?php else: ?>
                            <span class="badge badge-red">Out of Stock</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#777; border:none;">Seller:</td>
                    <td style="padding:6px 0; border:none;">@<?php echo $item['sellerName']; ?></td>
                </tr>
            </table>

            <!-- Description -->
            <?php if ($item['description'] != ""): ?>
                <div style="background:#f9f9f9; border-radius:8px; padding:14px; margin-bottom:20px;">
                    <strong>Description:</strong>
                    <p style="margin-top:6px; color:#555;"><?php echo $item['description']; ?></p>
                </div>
            <?php endif; ?>

            <!-- ACTION BUTTONS -->
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true): ?>

                <?php if ($item['quantity'] > 0 && $item['status'] == 'Available'): ?>
                    <!-- Add to Cart -->
                    <a href="cart/addToCart.php?id=<?php echo $item['itemID']; ?>&name=<?php echo urlencode($item['itemName']); ?>&price=<?php echo $item['price']; ?>"
                       class="btn btn-red"
                       style="width:100%; text-align:center; font-size:1rem; padding:14px; margin-bottom:10px; display:block;">
                        🛒 Add to Cart
                    </a>
                <?php else: ?>
                    <button class="btn btn-grey" style="width:100%; cursor:not-allowed; padding:14px; margin-bottom:10px;" disabled>
                        Out of Stock
                    </button>
                <?php endif; ?>

                <!-- Wishlist Button -->
                <?php if ($inWishlist): ?>
                    <a href="wishlist/removeFromWishlist.php?id=<?php echo $itemID; ?>&from=item"
                       class="btn btn-orange"
                       style="width:100%; text-align:center; display:block;">
                        ♥ Remove from Wishlist
                    </a>
                <?php else: ?>
                    <a href="wishlist/addToWishlist.php?id=<?php echo $itemID; ?>"
                       class="btn btn-blue"
                       style="width:100%; text-align:center; display:block;">
                        ♡ Save to Wishlist
                    </a>
                <?php endif; ?>

            <?php else: ?>
                <!-- Guest - show login prompt -->
                <a href="login.php" class="btn btn-red"
                   style="width:100%; text-align:center; font-size:1rem; padding:14px; display:block; margin-bottom:10px;">
                    Login to Buy
                </a>
                <a href="login.php" class="btn btn-blue"
                   style="width:100%; text-align:center; display:block;">
                    Login to Save to Wishlist
                </a>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
