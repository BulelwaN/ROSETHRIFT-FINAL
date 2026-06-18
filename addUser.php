<?php
$pageTitle="Add User"; $cssPath="../"; $rootPath="../";
include '../includes/DBConn.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../admin_login.php"); exit(); }
$success = ""; $error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    $status = $_POST['status'];
    $check = mysqli_query($conn, "SELECT userID FROM tblUser WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username or email already exists.";
    } else {
        $sql = "INSERT INTO tblUser (name,surname,email,phone,username,password,userType,status) VALUES ('$name','$surname','$email','$phone','$username','$password','$role','$status')";
        if (mysqli_query($conn, $sql)) $success = "User added successfully!";
        else $error = "Error: " . mysqli_error($conn);
    }
}
include '../includes/header.php';
?>
<div class="container" style="max-width:600px;">
  <h2 class="page-title">➕ Add New User</h2>
  <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
  <div class="card">
    <form method="POST">
      <div class="form-two-col">
        <div class="form-group"><label>First Name *</label><input type="text" name="name" required></div>
        <div class="form-group"><label>Surname</label><input type="text" name="surname"></div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Email *</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Phone</label><input type="tel" name="phone"></div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Username *</label><input type="text" name="username" required></div>
        <div class="form-group"><label>Password *</label><input type="text" name="password" required placeholder="Will be hashed"></div>
      </div>
      <div class="form-two-col">
        <div class="form-group"><label>Role</label>
          <select name="role"><option value="buyer">Buyer</option><option value="seller">Seller</option></select>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status"><option value="Pending">Pending</option><option value="Verified">Verified</option></select>
        </div>
      </div>
      <button type="submit" class="btn btn-green" style="width:100%;">Add User</button>
    </form>
  </div>
  <a href="manageUsers.php" class="btn btn-grey">← Back to Users</a>
</div>
<?php include '../includes/footer.php'; ?>
