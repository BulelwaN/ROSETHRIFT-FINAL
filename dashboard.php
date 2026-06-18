<?php

// Shows stats and quick links for the admin


$pageTitle = "Admin Dashboard";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

// If admin is not logged in, go to admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] != true) {
    header("Location: ../admin_login.php");
    exit();
}

// Count things for the stats boxes
$totalUsers    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblUser"))['c'];
$verifiedUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblUser WHERE status = 'Verified'"))['c'];
$pendingUsers  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblUser WHERE status = 'Pending'"))['c'];
$totalItems    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblClothes"))['c'];
$totalOrders   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblOrders"))['c'];
$unreadMsgs    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblMessages WHERE replied = 0"))['c'];
$pendingReqs   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM tblSellRequests WHERE status = 'Pending'"))['c'];

// Get recent orders to display
$recentOrders = mysqli_query($conn,
    "SELECT tblOrders.*, tblUser.name AS customerName
     FROM tblOrders
     JOIN tblUser ON tblOrders.userID = tblUser.userID
     ORDER BY orderDate DESC LIMIT 8"
);

include '../includes/header.php';
?>

<div class="container">

    <h2 class="page-title">🛠 Admin Dashboard</h2>
    <p style="margin-bottom:20px;">Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong>!</p>

    <!-- ===== STATS BOXES ===== -->
    <div class="stats-row">
        <div class="stat-box" style="background:#3498db;">
            <span class="big-number"><?php echo $totalUsers; ?></span>
            <span class="label">Total Users</span>
        </div>
        <div class="stat-box" style="background:#27ae60;">
            <span class="big-number"><?php echo $verifiedUsers; ?></span>
            <span class="label">Verified</span>
        </div>
        <div class="stat-box" style="background:#f39c12;">
            <span class="big-number"><?php echo $pendingUsers; ?></span>
            <span class="label">Pending</span>
        </div>
        <div class="stat-box" style="background:#8e44ad;">
            <span class="big-number"><?php echo $totalItems; ?></span>
            <span class="label">Listings</span>
        </div>
        <div class="stat-box" style="background:#c0392b;">
            <span class="big-number"><?php echo $totalOrders; ?></span>
            <span class="label">Orders</span>
        </div>
        <div class="stat-box" style="background:#16a085;">
            <span class="big-number"><?php echo $unreadMsgs; ?></span>
            <span class="label">New Messages</span>
        </div>
        <div class="stat-box" style="background:#d35400;">
            <span class="big-number"><?php echo $pendingReqs; ?></span>
            <span class="label">Sell Requests</span>
        </div>
    </div>

    <!-- ===== QUICK ACTION BUTTONS ===== -->
    <div class="card">
        <h3 style="margin-bottom:14px;">Quick Actions</h3>
        <a href="manageUsers.php" class="btn btn-blue" style="margin:5px;">👥 Manage Users</a>
        <a href="manageItems.php" class="btn btn-red" style="margin:5px;">👕 Manage Items</a>
        <a href="addItem.php" class="btn btn-green" style="margin:5px;">➕ Add New Item</a>
        <a href="addUser.php" class="btn btn-green" style="margin:5px;">➕ Add New User</a>
        <a href="sellRequests.php" class="btn btn-orange" style="margin:5px;">
            📤 Sell Requests (<?php echo $pendingReqs; ?>)
        </a>
        <a href="messages.php" class="btn btn-grey" style="margin:5px;">
            💬 Messages (<?php echo $unreadMsgs; ?>)
        </a>
        <a href="../logout.php" class="btn btn-grey" style="margin:5px;">Logout</a>
    </div>

    <!-- ===== RECENT ORDERS ===== -->
    <div class="card">
        <h3 style="margin-bottom:14px;">Recent Orders</h3>
        <div class="table-box">
            <table>
                <tr>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                <?php while ($order = mysqli_fetch_assoc($recentOrders)): ?>
                <tr>
                    <td><?php echo $order['orderNumber']; ?></td>
                    <td><?php echo $order['customerName']; ?></td>
                    <td>R<?php echo number_format($order['totalAmount'], 2); ?></td>
                    <td><span class="badge badge-green"><?php echo $order['status']; ?></span></td>
                    <td><?php echo date('d M Y', strtotime($order['orderDate'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
