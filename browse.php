<?php

// browse.php - Shop / Browse Items Page

$pageTitle = "Shop";
$cssPath   = "";
$rootPath  = "";

include 'includes/DBConn.php';
include 'includes/header.php';

// Get filter values from URL (if any)
$search    = isset($_GET['search'])    ? mysqli_real_escape_string($conn, $_GET['search'])    : "";
$category  = isset($_GET['category'])  ? mysqli_real_escape_string($conn, $_GET['category'])  : "";
$condition = isset($_GET['condition']) ? mysqli_real_escape_string($conn, $_GET['condition']) : "";
$minPrice  = isset($_GET['minPrice'])  && is_numeric($_GET['minPrice']) ? $_GET['minPrice']  : "";
$maxPrice  = isset($_GET['maxPrice'])  && is_numeric($_GET['maxPrice']) ? $_GET['maxPrice']  : "";

// Build the SQL query (start with all available items)
$sql = "SELECT tblClothes.*, tblUser.username AS seller
        FROM tblClothes
        JOIN tblUser ON tblClothes.sellerID = tblUser.userID
        WHERE tblClothes.status = 'Available' AND tblClothes.quantity > 0";

// Add filters if the user set them
if ($search    != "") $sql .= " AND tblClothes.itemName LIKE '%$search%'";
if ($category  != "") $sql .= " AND tblClothes.category = '$category'";
if ($condition != "") $sql .= " AND tblClothes.conditionItem = '$condition'";
if ($minPrice  != "") $sql .= " AND tblClothes.price >= $minPrice";
if ($maxPrice  != "") $sql .= " AND tblClothes.price <= $maxPrice";

$sql .= " ORDER BY tblClothes.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="container">

    <h2 class="page-title">🛍 Shop</h2>

    <!-- ===== FILTER BOX ===== -->
    <div class="card">
        <form method="GET" action="browse.php">
            <div style="display:flex; flex-wrap:wrap; gap:14px; align-items:flex-end;">

                <div class="form-group" style="margin:0; flex:1; min-width:160px;">
                    <label>Search Item:</label>
                    <input type="text" name="search" value="<?php echo $search; ?>" placeholder="e.g. Nike hoodie">
                </div>

                <div class="form-group" style="margin:0; flex:1; min-width:130px;">
                    <label>Category:</label>
                    <select name="category">
                        <option value="">All Categories</option>
                        <?php
                        $categories = ['Tops', 'Bottoms', 'Shoes', 'Dresses', 'Jackets', 'Accessories'];
                        foreach ($categories as $cat) {
                            $selected = ($category == $cat) ? "selected" : "";
                            echo "<option value='$cat' $selected>$cat</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="margin:0; flex:1; min-width:120px;">
                    <label>Condition:</label>
                    <select name="condition">
                        <option value="">Any Condition</option>
                        <?php
                        $conditions = ['New', 'Good', 'Fair'];
                        foreach ($conditions as $cond) {
                            $selected = ($condition == $cond) ? "selected" : "";
                            echo "<option value='$cond' $selected>$cond</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="margin:0; min-width:90px;">
                    <label>Min Price (R):</label>
                    <input type="number" name="minPrice" value="<?php echo $minPrice; ?>" min="0" placeholder="0">
                </div>

                <div class="form-group" style="margin:0; min-width:90px;">
                    <label>Max Price (R):</label>
                    <input type="number" name="maxPrice" value="<?php echo $maxPrice; ?>" min="0" placeholder="999">
                </div>

                <div>
                    <button type="submit" class="btn btn-red">Search</button>
                    <a href="browse.php" class="btn btn-grey btn-small" style="margin-left:6px;">Clear</a>
                </div>

            </div>
        </form>
    </div>

    <!-- ===== ITEMS GRID ===== -->
    <?php
    $itemCount = mysqli_num_rows($result);
    echo "<p style='margin-bottom:14px; color:#777;'>$itemCount item(s) found</p>";

    if ($itemCount > 0):
    ?>
    <div class="item-grid">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="item-card">
                <!-- Clicking the image goes to the item detail page -->
                <a href="viewItem.php?id=<?php echo $row['itemID']; ?>">
                    <?php
                    $imgSrc = ($row['image'] != "") ? $row['image'] : "images/shirt.jpeg";
                    ?>
                    <img src="<?php echo $imgSrc; ?>" alt="<?php echo $row['itemName']; ?>">
                </a>

                <div class="item-card-info">
                    <h4>
                        <a href="viewItem.php?id=<?php echo $row['itemID']; ?>"
                           style="color:#333; text-decoration:none;">
                            <?php echo $row['itemName']; ?>
                        </a>
                    </h4>
                    <div class="item-price">R<?php echo number_format($row['price'], 2); ?></div>
                    <div class="item-meta">
                        Size: <?php echo $row['size']; ?> &bull;
                        <?php echo $row['conditionItem']; ?> &bull;
                        <?php echo $row['category']; ?><br>
                        Seller: <?php echo $row['seller']; ?>
                    </div>

                    <?php if (isset($_SESSION['logged_in'])): ?>
                        <a href="cart/addToCart.php?id=<?php echo $row['itemID']; ?>&name=<?php echo urlencode($row['itemName']); ?>&price=<?php echo $row['price']; ?>"
                           class="btn btn-red btn-small" style="width:100%; text-align:center; margin-bottom:6px; display:block;">
                            🛒 Add to Cart
                        </a>
                        <a href="viewItem.php?id=<?php echo $row['itemID']; ?>"
                           class="btn btn-blue btn-small" style="width:100%; text-align:center; display:block;">
                            View Details
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-grey" style="width:100%; text-align:center;">
                            Login to Buy
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php else: ?>
        <div class="card" style="text-align:center; padding:40px;">
            <p>No items match your search. <a href="browse.php">Clear filters</a></p>
        </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>
