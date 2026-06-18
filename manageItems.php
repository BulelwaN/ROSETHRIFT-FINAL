<?php
$pageTitle="Manage Items"; $cssPath="../"; $rootPath="../";
include '../includes/DBConn.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../admin_login.php"); exit(); }
$message = "";
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM tblClothes WHERE itemID = '$id'");
    $message = "Item deleted.";
}
$items = mysqli_query($conn, "SELECT tblClothes.*, tblUser.username AS seller FROM tblClothes JOIN tblUser ON tblClothes.sellerID = tblUser.userID ORDER BY itemID DESC");
include '../includes/header.php';
?>
<div class="container">
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:16px;">
    <h2 class="page-title" style="margin:0;">👕 Manage Items</h2>
    <a href="addItem.php" class="btn btn-green">➕ Add New Item</a>
  </div>
  <?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
  <div class="card table-box">
    <table>
      <tr><th>ID</th><th>Image</th><th>Item Name</th><th>Category</th><th>Price</th><th>Qty</th><th>Seller</th><th>Status</th><th>Actions</th></tr>
      <?php while ($row = mysqli_fetch_assoc($items)): ?>
      <tr>
        <td><?php echo $row['itemID']; ?></td>
        <td><img src="../<?php echo $row['image'] ? $row['image'] : 'images/shirt.jpeg'; ?>" style="width:50px; height:50px; object-fit:cover; border-radius:5px;"></td>
        <td><?php echo $row['itemName']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td>R<?php echo number_format($row['price'], 2); ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo $row['seller']; ?></td>
        <td>
          <?php if ($row['status'] == 'Available'): ?>
            <span class="badge badge-green">Available</span>
          <?php else: ?>
            <span class="badge badge-red">Sold</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="editItem.php?id=<?php echo $row['itemID']; ?>" class="btn btn-blue btn-small">Edit</a>
          <a href="?delete=<?php echo $row['itemID']; ?>" class="btn btn-grey btn-small"
             onclick="return confirm('Delete this item?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
  <a href="dashboard.php" class="btn btn-grey" style="margin-top:14px;">← Dashboard</a>
</div>
<?php include '../includes/footer.php'; ?>
