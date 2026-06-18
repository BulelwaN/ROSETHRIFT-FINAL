<?php

// Sellers can update details of their own items


$pageTitle = "Edit Listing";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

// Must be logged in as a seller
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['user_type'] != 'seller') {
    header("Location: ../dashboard.php");
    exit();
}

$sellerID = $_SESSION['user_id'];
$itemID   = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// If no valid ID, go back
if ($itemID == 0) {
    header("Location: myListings.php");
    exit();
}

// Fetch the item — make sure it belongs to this seller
$fetchSQL = "SELECT * FROM tblClothes WHERE itemID = '$itemID' AND sellerID = '$sellerID'";
$fetch    = mysqli_query($conn, $fetchSQL);

if (mysqli_num_rows($fetch) == 0) {
    echo '<div class="container"><div class="alert alert-error">Item not found or you do not have permission. <a href="myListings.php">Back</a></div></div>';
    include '../includes/footer.php';
    exit();
}

$item = mysqli_fetch_assoc($fetch);

$successMessage = "";
$errorMessage   = "";

// ===== Handle SAVE =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $itemName    = mysqli_real_escape_string($conn, $_POST['itemName']);
    $brand       = mysqli_real_escape_string($conn, $_POST['brand']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price       = $_POST['price'];
    $size        = mysqli_real_escape_string($conn, $_POST['size']);
    $category    = $_POST['category'];
    $condition   = $_POST['condition'];
    $colour      = mysqli_real_escape_string($conn, $_POST['colour']);
    $quantity    = (int)$_POST['quantity'];
    $imagePath   = $item['image']; // keep old image by default

    // Handle new image upload if a file was chosen
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $newName    = "img_" . time() . "." . $ext;
            $uploadPath = "../uploads/" . $newName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $imagePath = "uploads/" . $newName;
            } else {
                $errorMessage = "Image upload failed.";
            }
        } else {
            $errorMessage = "Only JPG, PNG, GIF or WEBP images are allowed.";
        }
    }

    if ($errorMessage == "") {
        // Update the item in the database
        $updateSQL = "UPDATE tblClothes SET
                          itemName      = '$itemName',
                          brand         = '$brand',
                          description   = '$description',
                          price         = '$price',
                          size          = '$size',
                          category      = '$category',
                          conditionItem = '$condition',
                          colour        = '$colour',
                          quantity      = '$quantity',
                          image         = '$imagePath'
                      WHERE itemID = '$itemID' AND sellerID = '$sellerID'";

        if (mysqli_query($conn, $updateSQL)) {
            $successMessage = "Listing updated successfully!";
            // Refresh the item data so the form shows the new values
            $item['itemName']     = $itemName;
            $item['brand']        = $brand;
            $item['description']  = $description;
            $item['price']        = $price;
            $item['size']         = $size;
            $item['category']     = $category;
            $item['conditionItem']= $condition;
            $item['colour']       = $colour;
            $item['quantity']     = $quantity;
            $item['image']        = $imagePath;
        } else {
            $errorMessage = "Update failed. Please try again.";
        }
    }
}

include '../includes/header.php';
?>

<div class="container" style="max-width:720px;">

    <p style="margin-bottom:16px;">
        <a href="myListings.php" style="color:#c0392b; text-decoration:none;">← Back to My Listings</a>
    </p>

    <h2 class="page-title">✏️ Edit Listing</h2>

    <?php if ($successMessage != ""): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <?php if ($errorMessage != ""): ?>
        <div class="alert alert-error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="editListing.php?id=<?php echo $itemID; ?>" enctype="multipart/form-data">

            <div class="form-two-col">
                <div class="form-group">
                    <label>Item Name *</label>
                    <input type="text" name="itemName"
                           value="<?php echo $item['itemName']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" name="brand"
                           value="<?php echo $item['brand']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?php echo $item['description']; ?></textarea>
            </div>

            <div class="form-two-col">
                <div class="form-group">
                    <label>Price (R) *</label>
                    <input type="number" name="price" step="0.01" min="1"
                           value="<?php echo $item['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Quantity in Stock *</label>
                    <input type="number" name="quantity" min="0"
                           value="<?php echo $item['quantity']; ?>" required>
                </div>
            </div>

            <div class="form-two-col">
                <div class="form-group">
                    <label>Size</label>
                    <input type="text" name="size" value="<?php echo $item['size']; ?>">
                </div>
                <div class="form-group">
                    <label>Colour</label>
                    <input type="text" name="colour" value="<?php echo $item['colour']; ?>">
                </div>
            </div>

            <div class="form-two-col">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <?php
                        $cats = ['Tops','Bottoms','Shoes','Dresses','Jackets','Accessories'];
                        foreach ($cats as $c) {
                            $sel = ($item['category'] == $c) ? "selected" : "";
                            echo "<option value='$c' $sel>$c</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Condition *</label>
                    <select name="condition" required>
                        <?php
                        $conds = ['New','Good','Fair'];
                        foreach ($conds as $c) {
                            $sel = ($item['conditionItem'] == $c) ? "selected" : "";
                            echo "<option value='$c' $sel>$c</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Change Photo (leave blank to keep current image)</label>
                <?php if ($item['image'] != ""): ?>
                    <p style="margin-bottom:8px;">
                        <img src="../<?php echo $item['image']; ?>"
                             style="height:80px; border-radius:6px; object-fit:cover;">
                    </p>
                <?php endif; ?>
                <input type="file" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-red" style="width:100%;">Save Changes</button>
        </form>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
