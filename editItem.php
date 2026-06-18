<?php
$pageTitle="Edit Item"; $cssPath="../"; $rootPath="../";
include '../includes/DBConn.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../admin_login.php"); exit(); }
$id = isset($_GET['id']) ? $_GET['id'] : $_POST['itemID'];
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblClothes WHERE itemID='$id'"));
if (!$row) { header("Location: manageItems.php"); exit(); }
$success = ""; $error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemName = mysqli_real_escape_string($conn, $_POST['itemName']);
    $brand    = mysqli_real_escape_string($conn, $_POST['brand']);
    $desc     = mysqli_real_escape_string($conn, $_POST['description']);
    $price    = $_POST['price']; $qty = $_POST['quantity'];
    $size     = mysqli_real_escape_string($conn, $_POST['size']);
    $category = $_POST['category']; $condition = $_POST['condition'];
    $colour   = mysqli_real_escape_string($conn, $_POST['colour']);
    $status   = $_POST['status'];
    $imagePath = $row['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            $newName = "item_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $newName))
                $imagePath = "images/" . $newName;
        }
    }
    $sql = "UPDATE tblClothes SET itemName='$itemName', brand='$brand', description='$desc', category='$category', conditionItem='$condition', size='$size', colour='$colour', price='$price', image='$imagePath', quantity='$qty', status='$status' WHERE itemID='$id'";
    if (mysqli_query($conn, $sql)) { $success = "Item updated!"; $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblClothes WHERE itemID='$id'")); }
    else $error = "Error: " . mysqli_error($conn);
}
include '../includes/header.php';
?>
<div class="container" style="max-width:700px;">
  <h2 class="page-title">✏️ Edit Item #<?php echo $id; ?></h2>
  <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
  <div class="card">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="itemID" value="<?php echo $id; ?>">
      <div class="form-two-col">
        <div class="form-group"><label>Item Name *</label><input type="text" name="itemName" value="<?php echo $row['itemName']; ?>" required></div>
        <div class="form-group"><label>Brand</label><input type="text" name="brand" value="<?php echo $row['brand']; ?>"></div>
      </div>
      <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?php echo $row['description']; ?></textarea></div>
      <div class="form-two-col">
        <div class="form-group"><label>Price (R)</label><input type="number" name="price" step="0.01" value="<?php echo $row['price']; ?>" required></div>
        <div class="form-group"><label>Quantity</label><input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="0"></div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Category</label>
          <select name="category">
            <?php foreach (['Tops','Bottoms','Shoes','Dresses','Jackets','Accessories'] as $c): ?>
              <option value="<?php echo $c; ?>" <?php echo $row['category']==$c?'selected':''; ?>><?php echo $c; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group"><label>Condition</label>
          <select name="condition">
            <?php foreach (['New','Good','Fair'] as $c): ?>
              <option value="<?php echo $c; ?>" <?php echo $row['conditionItem']==$c?'selected':''; ?>><?php echo $c; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Size</label><input type="text" name="size" value="<?php echo $row['size']; ?>"></div>
        <div class="form-group"><label>Colour</label><input type="text" name="colour" value="<?php echo $row['colour']; ?>"></div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Status</label>
          <select name="status">
            <option value="Available" <?php echo $row['status']=='Available'?'selected':''; ?>>Available</option>
            <option value="Sold" <?php echo $row['status']=='Sold'?'selected':''; ?>>Sold</option>
          </select>
        </div>
        <div class="form-group"><label>Replace Image</label>
          <input type="file" name="image" accept="image/*">
          <?php if ($row['image']): ?><br><img src="../<?php echo $row['image']; ?>" style="height:55px; margin-top:6px; border-radius:4px;"><?php endif; ?>
        </div>
      </div>
      <button type="submit" class="btn btn-blue" style="width:100%;">Save Changes</button>
    </form>
  </div>
  <a href="manageItems.php" class="btn btn-grey">← Back to Items</a>
</div>
<?php include '../includes/footer.php'; ?>
