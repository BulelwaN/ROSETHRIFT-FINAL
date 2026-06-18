<?php

// customer_dashboard.php - Customer Dashboard
// Shown after a successful customer login


$pageTitle = "My Dashboard";
$cssPath   = "";
$rootPath  = "";

include 'includes/DBConn.php';

// Must be logged in as a customer
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: login.php");
    exit();
}

// ===== Fetch full user data from database using associative array =====
$userID = $_SESSION['user_id'];
$sql    = "SELECT * FROM tblUser WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql);
$user   = mysqli_fetch_assoc($result); // associative array — column names as keys

// Count this user's orders
$orderCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblOrders WHERE userID = '$userID'")
)['c'];

// Count this user's wishlist items
$wishCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblWishlist WHERE userID = '$userID'")
)['c'];

include 'includes/header.php';
?>

<div class="container">

    <!-- ===== LOGGED IN MESSAGE (required by spec) ===== -->
    <div class="alert alert-success" style="font-size:1.05rem; margin-bottom:24px;">
        ✅ User <strong><?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></strong> is logged in
    </div>

    <h2 class="page-title">👤 My Dashboard</h2>

    <!-- ===== USER DATA TABLE (associative array — column names as keys) ===== -->
    <div class="card" style="margin-bottom:24px;">
        <h3 style="margin-bottom:16px;">Your Account Details</h3>

        <div class="table-box">
            <table>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td><strong>User ID</strong></td>
                    <td><?php echo htmlspecialchars($user['userID']); ?></td>
                </tr>
                <tr>
                    <td><strong>First Name</strong></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Surname</strong></td>
                    <td><?php echo htmlspecialchars($user['surname']); ?></td>
                </tr>
                <tr>
                    <td><strong>Username</strong></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email Address</strong></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <td><strong>Phone Number</strong></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                </tr>
                <tr>
                    <td><strong>Account Type</strong></td>
                    <td><?php echo ucfirst(htmlspecialchars($user['userType'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Account Status</strong></td>
                    <td>
                        <?php if ($user['status'] == 'Verified'): ?>
                            <span class="badge badge-green">Verified</span>
                        <?php else: ?>
                            <span class="badge badge-yellow">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Member Since</strong></td>
                    <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ===== QUICK STATS ===== -->
    <div class="stats-row" style="margin-bottom:24px;">
        <div class="stat-box" style="background:#c0392b;">
            <span class="big-number"><?php echo $orderCount; ?></span>
            <span class="label">Orders Placed</span>
        </div>
        <div class="stat-box" style="background:#8e44ad;">
            <span class="big-number"><?php echo $wishCount; ?></span>
            <span class="label">Wishlist Items</span>
        </div>
        <div class="stat-box" style="background:#27ae60;">
            <span class="big-number"><?php echo count($_SESSION['cart'] ?? []); ?></span>
            <span class="label">Items in Cart</span>
        </div>
    </div>

    <!-- ===== QUICK ACTION BUTTONS ===== -->
    <div class="card">
        <h3 style="margin-bottom:14px;">Quick Actions</h3>
        <a href="browse.php"                    class="btn btn-red"   style="margin:5px;">🛍 Browse Items</a>
        <a href="cart/showCart.php"             class="btn btn-blue"  style="margin:5px;">🛒 View Cart</a>
        <a href="wishlist/viewWishlist.php"     class="btn btn-blue"  style="margin:5px;">♥ My Wishlist</a>
        <a href="cart/purchaseHistory.php"      class="btn btn-grey"  style="margin:5px;">📦 My Orders</a>
        <a href="contact.php"                   class="btn btn-grey"  style="margin:5px;">💬 Messages</a>
        <?php if ($_SESSION['user_type'] == 'seller'): ?>
            <a href="seller/sellRequest.php"    class="btn btn-green" style="margin:5px;">📤 Sell an Item</a>
            <a href="seller/myListings.php"     class="btn btn-green" style="margin:5px;">📋 My Listings</a>
        <?php endif; ?>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
