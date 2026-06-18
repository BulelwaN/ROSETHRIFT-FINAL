<?php

// purchaseHistory.php - Purchase History Report
// Shows the user all their past orders


$pageTitle = "My Orders";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Get all orders for this user
$orderSQL    = "SELECT * FROM tblOrders WHERE userID = '$userID' ORDER BY orderDate DESC";
$orderResult = mysqli_query($conn, $orderSQL);

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">📦 My Purchase History</h2>

    <?php if (mysqli_num_rows($orderResult) == 0): ?>
        <div class="card" style="text-align:center; padding:40px;">
            <p>No orders yet. <a href="../browse.php">Start shopping!</a></p>
        </div>

    <?php else: ?>
        <?php
        $allOrdersTotal = 0; // to add up all orders at the bottom

        while ($order = mysqli_fetch_assoc($orderResult)):
            $allOrdersTotal += $order['totalAmount'];

            // Get the items (lines) for this order
            $lineSQL    = "SELECT * FROM tblOrderLine WHERE orderID = '{$order['orderID']}'";
            $lineResult = mysqli_query($conn, $lineSQL);
        ?>

        <div class="card" style="border-left: 4px solid #c0392b; margin-bottom:20px;">
            <!-- Order header info -->
            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:8px; margin-bottom:12px;">
                <div>
                    <strong>Order #: <?php echo $order['orderNumber']; ?></strong>
                    <span class="badge badge-green" style="margin-left:8px;"><?php echo $order['status']; ?></span>
                </div>
                <div style="color:#777; font-size:0.85rem;">
                    Date: <?php echo date('d M Y H:i', strtotime($order['orderDate'])); ?>
                    &bull; Ref: <?php echo $order['sessionRef']; ?>
                </div>
            </div>

            <!-- Order lines table -->
            <div class="table-box">
                <table>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>

                    <?php while ($line = mysqli_fetch_assoc($lineResult)): ?>
                    <tr>
                        <td><?php echo $line['itemName']; ?></td>
                        <td><?php echo $line['qty']; ?></td>
                        <td>R<?php echo number_format($line['unitPrice'], 2); ?></td>
                        <td>R<?php echo number_format($line['subtotal'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>

                    <!-- Order total row -->
                    <tr style="background:#fef6f5;">
                        <td colspan="3" style="text-align:right; font-weight:bold;">Order Total:</td>
                        <td style="font-weight:bold; color:#c0392b;">
                            R<?php echo number_format($order['totalAmount'], 2); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <?php endwhile; ?>

        <!-- Grand total of all orders -->
        <div class="card" style="text-align:right;">
            <strong style="font-size:1.2rem; color:#c0392b;">
                Grand Total (All Orders): R<?php echo number_format($allOrdersTotal, 2); ?>
            </strong>
        </div>

    <?php endif; ?>

    <a href="../browse.php" class="btn btn-red" style="margin-top:16px;">Continue Shopping</a>
</div>

<?php include '../includes/footer.php'; ?>
