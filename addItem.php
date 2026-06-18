<?php
$pageTitle="Add Item"; $cssPath="../"; $rootPath="../";
include '../includes/DBConn.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../admin_login.php"); exit(); }
$success = ""; $error = "";
$sellers = mysqli_query($conn, "SELECT userID, name, username FROM tblUser WHERE userType='seller' AND status='Verified'");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemName = mysqli_real_escape_string($conn, $_POST['itemName']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $category = $_POST['category'];
    $condition = $_POST['condition'];
    $colour = mysqli_real_escape_string($conn, $_POST['colour']);
    $qty = $_POST['quantity'];
    $sellerID = $_POST['sellerID'];
    $imagePath = "images/shirt.jpeg"; // default image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            $newName = "item_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $newName))
                $imagePath = "images/" . $newName;
        }
    }
    $sql = "INSERT INTO tblClothes (itemName,brand,description,category,conditionItem,size,colour,price,image,quantity,sellerID,status) VALUES ('$itemName','$brand','$desc','$category','$condition','$size','$colour','$price','$imagePath','$qty','$sellerID','Available')";
    if (mysqli_query($conn, $sql)) $success = "Item added successfully!";
    else $error = "Error: " . mysqli_error($conn);
}
include '../includes/header.php';
?>
<div class="container" style="max-width:700px;">
  <h2 class="page-title">➕ Add New Item</h2>
  <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
  <div class="card">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-two-col">
        <div class="form-group"><label>Item Name *</label><input type="text" name="itemName" required></div>
        <div class="form-group"><label>Brand</label><input type="text" name="brand"></div>
      </div>
      <div class="form-group"><label>Description</label><textarea name="description" rows="3"></textarea></div>
      <div class="form-two-col">
        <div class="form-group"><label>Price (R) *</label><input type="number" name="price" step="0.01" min="0" required></div>
        <div class="form-group"><label>Quantity *</label><input type="number" name="quantity" min="1" value="1" required></div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Category *</label>
          <select name="category" required><option value="">Select...</option>
            <option value="Tops">Tops</option><option value="Bottoms">Bottoms</option>
            <option value="Shoes">Shoes</option><option value="Dresses">Dresses</option>
            <option value="Jackets">Jackets</option><option value="Accessories">Accessories</option>
          </select>
        </div>
        <div class="form-group"><label>Condition *</label>
          <select name="condition" required><option value="New">New</option><option value="Good">Good</option><option value="Fair">Fair</option></select>
        </div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Size</label><input type="text" name="size" placeholder="e.g. M, L, 32"></div>
        <div class="form-group"><label>Colour</label><input type="text" name="colour"></div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Assign to Seller *</label>
          <select name="sellerID" required><option value="">Choose seller...</option>
            <?php while ($s = mysqli_fetch_assoc($sellers)): ?>
              <option value="<?php echo $s['userID']; ?>"><?php echo $s['name']; ?> (<?php echo $s['username']; ?>)</option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group"><label>Upload Image</label><input type="file" name="image" accept="image/*"></div>
      </div>
      <button type="submit" class="btn btn-green" style="width:100%;">Add Item</button>
    </form>
  </div>
  <a href="manageItems.php" class="btn btn-grey">← Back to Items</a>
</div>
<?php include '../includes/footer.php'; ?>
