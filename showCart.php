<?php

// Displays all items in the user's cart
// =============================================

$pageTitle = "My Cart";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

// Must be logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">🛒 My Cart</h2>

    <!-- Show "added" message if item was just added -->
    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">Item added to your cart!</div>
    <?php endif; ?>

    <?php if (empty($_SESSION['cart'])): ?>
        <!-- Cart is empty -->
        <div class="card" style="text-align:center; padding:40px;">
            <p style="font-size:1.1rem; color:#777; margin-bottom:16px;">Your cart is empty.</p>
            <a href="../browse.php" class="btn btn-red">Start Shopping</a>
        </div>

    <?php else: ?>
        <!-- Cart has items -->
        <div class="card">
            <div class="table-box">
                <table>
                    <tr>
                        <th>Item Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    $grandTotal = 0;

                    // Loop through each item in the cart
                    foreach ($_SESSION['cart'] as $id => $item) {
                        $subtotal    = $item['price'] * $item['quantity'];
                        $grandTotal += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td>R<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <!-- Update quantity form -->
                            <form method="POST" action="updateQty.php" style="display:inline-flex; gap:5px;">
                                <input type="hidden" name="itemID" value="<?php echo $id; ?>">
                                <input type="number" name="newQty" class="qty-input"
                                       value="<?php echo $item['quantity']; ?>"
                                       min="1" max="<?php echo $item['maxQty']; ?>">
                                <button type="submit" class="btn btn-blue btn-small">Update</button>
                            </form>
                        </td>
                        <td>R<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <a href="removeItem.php?id=<?php echo $id; ?>"
                               class="btn btn-grey btn-small"
                               onclick="return confirm('Remove this item?')">
                                Remove
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

            <!-- Cart total -->
            <div class="cart-total">
                Total: R<?php echo number_format($grandTotal, 2); ?>
            </div>

            <!-- Cart action buttons -->
            <div style="display:flex; flex-wrap:wrap; gap:10px; justify-content:flex-end; margin-top:10px;">
                <a href="../browse.php" class="btn btn-grey">Continue Shopping</a>
                <a href="emptyCart.php" class="btn btn-orange"
                   onclick="return confirm('Remove all items from your cart?')">
                    Empty Cart
                </a>
                <a href="checkout.php" class="btn btn-red">
                    Proceed to Checkout →
                </a>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
