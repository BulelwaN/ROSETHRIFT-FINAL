<?php
$pageTitle="Edit User"; $cssPath="../"; $rootPath="../";
include '../includes/DBConn.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../admin_login.php"); exit(); }
$id = isset($_GET['id']) ? $_GET['id'] : $_POST['userID'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblUser WHERE userID = '$id'"));
if (!$user) { header("Location: manageUsers.php"); exit(); }
$success = ""; $error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = $_POST['role'];
    $status = $_POST['status'];
    $sql = "UPDATE tblUser SET name='$name', surname='$surname', email='$email', phone='$phone', username='$username', userType='$role', status='$status' WHERE userID='$id'";
    if (mysqli_query($conn, $sql)) { $success = "User updated!"; $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblUser WHERE userID='$id'")); }
    else $error = "Error: " . mysqli_error($conn);
}
include '../includes/header.php';
?>
<div class="container" style="max-width:600px;">
  <h2 class="page-title">✏️ Edit User #<?php echo $id; ?></h2>
  <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
  <div class="card">
    <form method="POST">
      <input type="hidden" name="userID" value="<?php echo $id; ?>">
      <div class="form-two-col">
        <div class="form-group"><label>First Name</label><input type="text" name="name" value="<?php echo $user['name']; ?>" required></div>
        <div class="form-group"><label>Surname</label><input type="text" name="surname" value="<?php echo $user['surname']; ?>"></div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo $user['email']; ?>" required></div>
        <div class="form-group"><label>Phone</label><input type="tel" name="phone" value="<?php echo $user['phone']; ?>"></div>
      </div>
      <div class="form-group"><label>Username</label><input type="text" name="username" value="<?php echo $user['username']; ?>" required></div>
      <div class="form-two-col">
        <div class="form-group"><label>Role</label>
          <select name="role">
            <option value="buyer" <?php echo $user['userType']=='buyer'?'selected':''; ?>>Buyer</option>
            <option value="seller" <?php echo $user['userType']=='seller'?'selected':''; ?>>Seller</option>
          </select>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status">
            <option value="Pending" <?php echo $user['status']=='Pending'?'selected':''; ?>>Pending</option>
            <option value="Verified" <?php echo $user['status']=='Verified'?'selected':''; ?>>Verified</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-blue" style="width:100%;">Save Changes</button>
    </form>
  </div>
  <a href="manageUsers.php" class="btn btn-grey">← Back to Users</a>
</div>
<?php include '../includes/footer.php'; ?>
