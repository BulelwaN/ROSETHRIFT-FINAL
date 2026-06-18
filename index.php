<?php
// index.php - Homepage / Startup Page
// This is the first page users see

$pageTitle = "Home";
$cssPath   = "";
$rootPath  = "";

include 'includes/DBConn.php';
include 'includes/header.php';

// Get the 6 newest available items to show on homepage
$sql    = "SELECT * FROM tblClothes WHERE status = 'Available' AND quantity > 0 ORDER BY created_at DESC LIMIT 6";
$result = mysqli_query($conn, $sql);
?>

<div class="container">

    <!-- ===== HERO BANNER ===== -->
    <div class="hero">
        <h1>🌹 Welcome to Rosethrift</h1>
        <p>Affordable second-hand fashion for students. Buy, sell, and discover unique clothing.</p>

        <a href="browse.php" class="btn" style="background:white; color:#c0392b; font-weight:bold; font-size:1rem; padding:14px 32px;">
            🛍 Shop Now
        </a>

        <?php if (!isset($_SESSION['logged_in'])): ?>
            <!-- Show Register button only if not logged in -->
            <a href="register.php" class="btn" style="background:transparent; color:white; border:2px solid white; margin-left:10px; padding:12px 30px;">
                Register Free
            </a>
        <?php endif; ?>
    </div>

    <!-- ===== CATEGORY SHORTCUTS ===== -->
    <h2 class="page-title">Browse by Category</h2>

    <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:36px;">
        <?php
        // Each category as a clickable pill/button
        $categories = [
            ['name' => 'Tops',        'icon' => '👕'],
            ['name' => 'Bottoms',     'icon' => '👖'],
            ['name' => 'Jackets',     'icon' => '🧥'],
            ['name' => 'Shoes',       'icon' => '👟'],
            ['name' => 'Dresses',     'icon' => '👗'],
            ['name' => 'Accessories', 'icon' => '👜'],
        ];
        foreach ($categories as $cat) {
            echo '<a href="browse.php?category=' . $cat['name'] . '"
                     style="display:inline-block; padding:12px 22px; background:white;
                            border:2px solid #c0392b; border-radius:30px; color:#c0392b;
                            text-decoration:none; font-weight:600; font-size:0.95rem;
                            transition:all 0.2s; box-shadow:0 2px 6px rgba(0,0,0,0.07);"
                     onmouseover="this.style.background=\'#c0392b\'; this.style.color=\'white\';"
                     onmouseout="this.style.background=\'white\'; this.style.color=\'#c0392b\';">
                     ' . $cat['icon'] . ' ' . $cat['name'] . '
                 </a>';
        }
        ?>
    </div>

    <!-- ===== LATEST LISTINGS ===== -->
    <h2 class="page-title">Latest Items</h2>

    <div class="item-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $image = $row['image'] ? $row['image'] : "images/shirt.jpeg";
        ?>
                <div class="item-card">
                    <!-- Click image to see full detail page -->
                    <a href="viewItem.php?id=<?php echo $row['itemID']; ?>">
                        <img src="<?php echo $image; ?>" alt="<?php echo $row['itemName']; ?>">
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
                            Condition: <?php echo $row['conditionItem']; ?>
                        </div>

                        <?php if (isset($_SESSION['logged_in'])): ?>
                            <a href="cart/addToCart.php?id=<?php echo $row['itemID']; ?>&name=<?php echo urlencode($row['itemName']); ?>&price=<?php echo $row['price']; ?>"
                               class="btn btn-red" style="width:100%; text-align:center;">
                                Add to Cart
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-grey" style="width:100%; text-align:center;">
                                Login to Buy
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>No items available yet.</p>";
        }
        ?>
    </div>

    <!-- Button to see all items -->
    <div style="text-align:center; margin-top:25px;">
        <a href="browse.php" class="btn btn-red">View All Items</a>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
