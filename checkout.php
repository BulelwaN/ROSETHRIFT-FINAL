<?php
// Saves the order to the database
// Reduces stock, empties cart after payment


$pageTitle = "Checkout";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

// If NOT logged in, send to login page first
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

// If cart is empty, go back to cart page
if (empty($_SESSION['cart'])) {
    header("Location: showCart.php");
    exit();
}

$successMessage = "";
$errorMessage   = "";
$orderNumber    = "";

// ===== ProcessInput() — When user clicks "Confirm Order" =====
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmOrder'])) {

    $userID = $_SESSION['user_id'];

    // Calculate the total
    $grandTotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $grandTotal += $item['price'] * $item['quantity'];
    }

    // Generate a unique order number (e.g. ORD-A3F2B1)
    $orderNumber = "ORD-" . strtoupper(substr(md5(uniqid()), 0, 8));
    $sessionRef  = session_id(); // save session ID as reference

    // Save the order header to tblOrders
    $orderSQL = "INSERT INTO tblOrders (orderNumber, sessionRef, userID, totalAmount, status)
                 VALUES ('$orderNumber', '$sessionRef', '$userID', '$grandTotal', 'Completed')";

    if (mysqli_query($conn, $orderSQL)) {

        // Get the new order's ID
        $orderID = mysqli_insert_id($conn);

        // Save each item as a line in tblOrderLine
        foreach ($_SESSION['cart'] as $itemID => $item) {
            $itemName  = mysqli_real_escape_string($conn, $item['name']);
            $qty       = $item['quantity'];
            $unitPrice = $item['price'];
            $subtotal  = $unitPrice * $qty;

            $lineSQL = "INSERT INTO tblOrderLine (orderID, itemID, itemName, qty, unitPrice, subtotal)
                        VALUES ('$orderID', '$itemID', '$itemName', '$qty', '$unitPrice', '$subtotal')";
            mysqli_query($conn, $lineSQL);

            // Reduce the stock in tblClothes
            $updateSQL = "UPDATE tblClothes SET quantity = quantity - $qty WHERE itemID = '$itemID'";
            mysqli_query($conn, $updateSQL);

            // If stock hits 0, mark as Sold
            $conn->query("UPDATE tblClothes SET status = 'Sold' WHERE itemID = '$itemID' AND quantity <= 0");
        }

        // EmptyCart() — clear the cart after successful order
        $_SESSION['cart'] = [];
        $successMessage   = "Order placed!";

    } else {
        $errorMessage = "Order failed. Please try again.";
    }
}

// Build display total (for the summary — before order is placed)
$displayTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $displayTotal += $item['price'] * $item['quantity'];
}

include '../includes/header.php';
?>

<div class="container" style="max-width:680px;">
    <h2 class="page-title">✅ Checkout</h2>

    <?php if ($successMessage != ""): ?>
        <!-- ORDER SUCCESS MESSAGE -->
        <div class="card" style="text-align:center; padding:40px;">
            <div style="font-size:3rem; margin-bottom:12px;">🎉</div>
            <h3 style="color:#27ae60; margin-bottom:10px;">Order Placed Successfully!</h3>
            <p>Your order number is: <strong style="font-size:1.2rem;"><?php echo $orderNumber; ?></strong></p>
            <p style="color:#777; margin-top:6px;">Session Reference: <?php echo session_id(); ?></p>
            <div style="margin-top:24px; display:flex; gap:12px; justify-content:center;">
                <a href="purchaseHistory.php" class="btn btn-green">View My Orders</a>
                <a href="../browse.php" class="btn btn-red">Continue Shopping</a>
            </div>
        </div>

    <?php else: ?>

        <?php if ($errorMessage != ""): ?>
            <div class="alert alert-error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- ORDER SUMMARY TABLE -->
        <div class="card">
            <h3 style="margin-bottom:16px;">Order Summary</h3>
            <div class="table-box">
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>R<?php echo number_format($item['price'], 2); ?></td>
                        <td>R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="cart-total">Grand Total: R<?php echo number_format($displayTotal, 2); ?></div>
        </div>

        <!-- CONFIRM ORDER BUTTON -->
        <div class="card">
            <p>Delivery to: <strong><?php echo $_SESSION['user_name']; ?></strong></p>
            <p style="color:#777; margin-bottom:20px; font-size:0.9rem;">Payment on delivery (cash).</p>

            <form method="POST" action="checkout.php">
                <button type="submit" name="confirmOrder" value="1"
                        class="btn btn-red" style="width:100%; font-size:1.05rem; padding:14px;">
                    Confirm &amp; Place Order
                </button>
            </form>

            <a href="showCart.php" class="btn btn-grey" style="width:100%; text-align:center; margin-top:10px; display:block;">
                ← Back to Cart
            </a>
        </div>

    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
