<?php
$pageTitle="Sell Requests"; $cssPath="../"; $rootPath="../";
include '../includes/DBConn.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../admin_login.php"); exit(); }
$message = "";
// APPROVE: add item to tblClothes
if (isset($_GET['approve'])) {
    $id  = $_GET['approve'];
    $req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblSellRequests WHERE requestID='$id'"));
    if ($req) {
        $iN=$req['itemName']; $ib=$req['brand']; $id2=$req['description'];
        $ic=$req['category']; $ico=$req['conditionItem']; $is=$req['size'];
        $icol=$req['colour']; $ip=$req['price']; $img=$req['image']; $sid=$req['sellerID'];
        $insertSQL = "INSERT INTO tblClothes (itemName,brand,description,category,conditionItem,size,colour,price,image,quantity,sellerID,status) VALUES ('$iN','$ib','$id2','$ic','$ico','$is','$icol','$ip','$img',1,'$sid','Available')";
        if (mysqli_query($conn, $insertSQL)) {
            mysqli_query($conn, "UPDATE tblSellRequests SET status='Approved' WHERE requestID='$id'");
            $message = "Request approved! Item is now live in the shop.";
        }
    }
}
// REJECT
if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    mysqli_query($conn, "UPDATE tblSellRequests SET status='Rejected' WHERE requestID='$id'");
    $message = "Request rejected.";
}
$requests = mysqli_query($conn, "SELECT tblSellRequests.*, tblUser.name AS sellerName, tblUser.username FROM tblSellRequests JOIN tblUser ON tblSellRequests.sellerID = tblUser.userID ORDER BY tblSellRequests.created_at DESC");
include '../includes/header.php';
?>
<div class="container">
  <h2 class="page-title">📤 Seller Requests</h2>
  <?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
  <div class="card table-box">
    <table>
      <tr><th>Photo</th><th>Item</th><th>Price</th><th>Category</th><th>Seller</th><th>Status</th><th>Actions</th></tr>
      <?php while ($req = mysqli_fetch_assoc($requests)): ?>
      <tr>
        <td><?php if ($req['image']): ?><img src="../<?php echo $req['image']; ?>" style="width:50px;height:50px;object-fit:cover;border-radius:5px;"><?php else: ?>-<?php endif; ?></td>
        <td>
          <strong><?php echo $req['itemName']; ?></strong><br>
          <small style="color:#888;"><?php echo substr($req['description'], 0, 60); ?></small>
        </td>
        <td>R<?php echo number_format($req['price'], 2); ?></td>
        <td><?php echo $req['category']; ?></td>
        <td><?php echo $req['sellerName']; ?><br><small><?php echo $req['username']; ?></small></td>
        <td>
          <?php
          $bc = "badge-yellow";
          if ($req['status']=='Approved') $bc="badge-green";
          if ($req['status']=='Rejected') $bc="badge-red";
          ?>
          <span class="badge <?php echo $bc; ?>"><?php echo $req['status']; ?></span>
        </td>
        <td>
          <?php if ($req['status'] == 'Pending'): ?>
            <a href="?approve=<?php echo $req['requestID']; ?>" class="btn btn-green btn-small"
               onclick="return confirm('Approve and publish this item?')">Approve</a>
            <a href="?reject=<?php echo $req['requestID']; ?>" class="btn btn-grey btn-small"
               onclick="return confirm('Reject this request?')">Reject</a>
          <?php else: ?>
            <em style="color:#999;"><?php echo $req['status']; ?></em>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
  <a href="dashboard.php" class="btn btn-grey" style="margin-top:14px;">← Dashboard</a>
</div>
<?php include '../includes/footer.php'; ?>
