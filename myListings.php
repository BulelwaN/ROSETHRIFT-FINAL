<?php

// Sellers can view, edit and delete their own listings


$pageTitle = "My Listings";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

// Must be logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

// Only sellers can use this page
if ($_SESSION['user_type'] != 'seller') {
    echo '<div class="container"><div class="alert alert-error">Only sellers can access this page. <a href="../dashboard.php">Go back</a></div></div>';
    include '../includes/footer.php';
    exit();
}

$sellerID = $_SESSION['user_id'];

// ===== Handle DELETE request =====
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteID = (int)$_GET['delete'];

    // Make sure this item belongs to the logged-in seller before deleting
    $checkSQL = "SELECT itemID FROM tblClothes WHERE itemID = '$deleteID' AND sellerID = '$sellerID'";
    $check    = mysqli_query($conn, $checkSQL);

    if (mysqli_num_rows($check) == 1) {
        mysqli_query($conn, "DELETE FROM tblClothes WHERE itemID = '$deleteID'");
        $successMessage = "Item deleted successfully.";
    } else {
        $errorMessage = "Could not delete item.";
    }
}

// Get all this seller's listings
$sql    = "SELECT * FROM tblClothes WHERE sellerID = '$sellerID' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

include '../includes/header.php';
?>

<div class="container">

    <h2 class="page-title">📦 My Listings</h2>

    <!-- Success / Error messages -->
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <!-- Add New Item button -->
    <div style="margin-bottom:20px;">
        <a href="sellRequest.php" class="btn btn-red">+ Submit a New Item</a>
    </div>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="card" style="text-align:center; padding:40px;">
            <p style="color:#777; margin-bottom:16px;">You have not listed any items yet.</p>
            <a href="sellRequest.php" class="btn btn-red">Submit Your First Item</a>
        </div>

    <?php else: ?>
        <div class="card">
            <div class="table-box">
                <table>
                    <tr>
                        <th>Image</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Condition</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $imgSrc    = ($row['image'] != "") ? "../" . $row['image'] : "../images/shirt.jpeg";
                        $statusBadge = "badge-green";
                        if ($row['status'] == 'Sold')   $statusBadge = "badge-red";
                        if ($row['status'] == 'Hidden') $statusBadge = "badge-yellow";
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo $imgSrc; ?>"
                                     alt="<?php echo $row['itemName']; ?>"
                                     style="width:55px; height:55px; object-fit:cover; border-radius:6px;">
                            </td>
                            <td><strong><?php echo $row['itemName']; ?></strong><br>
                                <small style="color:#888;"><?php echo $row['brand']; ?></small>
                            </td>
                            <td>R<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['conditionItem']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>
                                <span class="badge <?php echo $statusBadge; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <!-- Edit button -->
                                <a href="editListing.php?id=<?php echo $row['itemID']; ?>"
                                   class="btn btn-blue btn-small" style="margin-right:4px;">Edit</a>

                                <!-- Delete button -->
                                <a href="myListings.php?delete=<?php echo $row['itemID']; ?>"
                                   class="btn btn-grey btn-small"
                                   onclick="return confirm('Delete this listing permanently?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
