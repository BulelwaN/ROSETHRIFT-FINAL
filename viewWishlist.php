<?php

// wishlist/viewWishlist.php - ViewWishlist()
// Shows the user all items the user has saved


$pageTitle = "My Wishlist";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

// Must be logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Get all wishlist items for this user (join with tblClothes for full details)
$sql = "SELECT tblWishlist.wishlistID, tblClothes.*
        FROM tblWishlist
        JOIN tblClothes ON tblWishlist.itemID = tblClothes.itemID
        WHERE tblWishlist.userID = '$userID'
        ORDER BY tblWishlist.saved_at DESC";

$result = mysqli_query($conn, $sql);

include '../includes/header.php';
?>

<div class="container">

    <h2 class="page-title">♥ My Wishlist</h2>

    <!-- Show confirmation if item was just added -->
    <?php if (isset($_GET['removed'])): ?>
        <div class="alert alert-info">Item removed from your wishlist.</div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <!-- Wishlist is empty -->
        <div class="card" style="text-align:center; padding:40px;">
            <p style="font-size:1.1rem; color:#777; margin-bottom:16px;">
                Your wishlist is empty. Browse the shop and save items you like!
            </p>
            <a href="../browse.php" class="btn btn-red">Browse Items</a>
        </div>

    <?php else: ?>
        <!-- Show wishlist items as cards -->
        <div class="item-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php $imgSrc = ($row['image'] != "") ? $row['image'] : "images/shirt.jpeg"; ?>

                <div class="item-card">
                    <!-- Click image to view item detail -->
                    <a href="../viewItem.php?id=<?php echo $row['itemID']; ?>">
                        <img src="../<?php echo $imgSrc; ?>" alt="<?php echo $row['itemName']; ?>">
                    </a>

                    <div class="item-card-info">
                        <h4>
                            <a href="../viewItem.php?id=<?php echo $row['itemID']; ?>"
                               style="color:#333; text-decoration:none;">
                                <?php echo $row['itemName']; ?>
                            </a>
                        </h4>
                        <div class="item-price">R<?php echo number_format($row['price'], 2); ?></div>
                        <div class="item-meta">
                            Size: <?php echo $row['size']; ?> &bull;
                            <?php echo $row['conditionItem']; ?>
                        </div>

                        <!-- Stock badge -->
                        <?php if ($row['quantity'] > 0 && $row['status'] == 'Available'): ?>
                            <span class="badge badge-green" style="margin-bottom:8px; display:inline-block;">In Stock</span>
                        <?php else: ?>
                            <span class="badge badge-red" style="margin-bottom:8px; display:inline-block;">Out of Stock</span>
                        <?php endif; ?>

                        <!-- Add to cart if available -->
                        <?php if ($row['quantity'] > 0 && $row['status'] == 'Available'): ?>
                            <a href="../cart/addToCart.php?id=<?php echo $row['itemID']; ?>&name=<?php echo urlencode($row['itemName']); ?>&price=<?php echo $row['price']; ?>"
                               class="btn btn-red btn-small" style="width:100%; text-align:center; display:block; margin-bottom:6px;">
                                🛒 Add to Cart
                            </a>
                        <?php endif; ?>

                        <!-- Remove from wishlist -->
                        <a href="removeFromWishlist.php?id=<?php echo $row['itemID']; ?>"
                           class="btn btn-grey btn-small" style="width:100%; text-align:center; display:block;"
                           onclick="return confirm('Remove from wishlist?')">
                            Remove
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
