<?php

// Sellers upload items for admin approval


$pageTitle = "Sell an Item";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

// Must be logged in as a seller
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['user_type'] != 'seller') {
    echo '<div class="container"><div class="alert alert-error">Only sellers can submit items. <a href="../dashboard.php">Go back</a></div></div>';
    include '../includes/footer.php';
    exit();
}

$successMessage = "";
$errorMessage   = "";
$sellerID = $_SESSION['user_id'];

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $itemName    = mysqli_real_escape_string($conn, $_POST['itemName']);
    $brand       = mysqli_real_escape_string($conn, $_POST['brand']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price       = $_POST['price'];
    $size        = mysqli_real_escape_string($conn, $_POST['size']);
    $category    = $_POST['category'];
    $condition   = $_POST['condition'];
    $colour      = mysqli_real_escape_string($conn, $_POST['colour']);

    // Handle the image upload
    $imagePath = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedTypes  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($fileExtension, $allowedTypes)) {
            // Give the file a unique name so files don't overwrite each other
            $newFileName = "img_" . time() . "." . $fileExtension;
            $uploadPath  = "../uploads/" . $newFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $imagePath = "uploads/" . $newFileName;
            } else {
                $errorMessage = "Image upload failed.";
            }
        } else {
            $errorMessage = "Only JPG, PNG, GIF or WEBP images allowed.";
        }
    }

    if ($errorMessage == "") {
        // Save the request to the database (status = Pending)
        $sql = "INSERT INTO tblSellRequests (sellerID, itemName, brand, description, price, size, category, conditionItem, colour, image, status)
                VALUES ('$sellerID', '$itemName', '$brand', '$description', '$price', '$size', '$category', '$condition', '$colour', '$imagePath', 'Pending')";

        if (mysqli_query($conn, $sql)) {
            $successMessage = "Item submitted! The admin will review it and approve or reject it.";
        } else {
            $errorMessage = "Submission failed. Please try again.";
        }
    }
}

// Get this seller's previous submissions
$mySQL    = "SELECT * FROM tblSellRequests WHERE sellerID = '$sellerID' ORDER BY created_at DESC";
$myResult = mysqli_query($conn, $mySQL);

include '../includes/header.php';
?>

<div class="container" style="max-width:720px;">

    <h2 class="page-title">📤 Submit Item for Sale</h2>

    <?php if ($successMessage != ""): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <?php if ($errorMessage != ""): ?>
        <div class="alert alert-error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <!-- ===== SUBMISSION FORM ===== -->
    <div class="card">
        <form method="POST" action="sellRequest.php" enctype="multipart/form-data">

            <div class="form-two-col">
                <div class="form-group">
                    <label>Item Name *</label>
                    <input type="text" name="itemName" placeholder="e.g. Nike Hoodie" required>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" name="brand" placeholder="e.g. Nike, H&M">
                </div>
            </div>

            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="3" placeholder="Describe the item — size, colour, any damage?" required></textarea>
            </div>

            <div class="form-two-col">
                <div class="form-group">
                    <label>Asking Price (R) *</label>
                    <input type="number" name="price" step="0.01" min="1" required>
                </div>
                <div class="form-group">
                    <label>Size</label>
                    <input type="text" name="size" placeholder="e.g. M, L, 32, 9">
                </div>
            </div>

            <div class="form-two-col">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="">Choose category...</option>
                        <option value="Tops">Tops</option>
                        <option value="Bottoms">Bottoms</option>
                        <option value="Shoes">Shoes</option>
                        <option value="Dresses">Dresses</option>
                        <option value="Jackets">Jackets</option>
                        <option value="Accessories">Accessories</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Condition *</label>
                    <select name="condition" required>
                        <option value="">Choose condition...</option>
                        <option value="New">New</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                    </select>
                </div>
            </div>

            <div class="form-two-col">
                <div class="form-group">
                    <label>Colour</label>
                    <input type="text" name="colour" placeholder="e.g. Blue, Red">
                </div>
                <div class="form-group">
                    <label>Upload a Photo</label>
                    <input type="file" name="image" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn btn-red" style="width:100%;">Submit for Admin Approval</button>
        </form>
    </div>

    <!-- ===== MY SUBMISSIONS TABLE ===== -->
    <div class="card">
        <h3 style="margin-bottom:16px;">My Submissions</h3>

        <?php if (mysqli_num_rows($myResult) > 0): ?>
            <div class="table-box">
                <table>
                    <tr>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date Submitted</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($myResult)): ?>
                    <tr>
                        <td><?php echo $row['itemName']; ?></td>
                        <td>R<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td>
                            <?php
                            $badgeClass = "badge-yellow";
                            if ($row['status'] == 'Approved') $badgeClass = "badge-green";
                            if ($row['status'] == 'Rejected') $badgeClass = "badge-red";
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $row['status']; ?></span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php else: ?>
            <p>No submissions yet.</p>
        <?php endif; ?>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
