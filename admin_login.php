<?php

// admin_login.php - Admin Login Page


$pageTitle = "Admin Login";
$cssPath   = "";
$rootPath  = "";

include 'includes/DBConn.php';

// If admin already logged in, go to admin dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) {
    header("Location: admin/dashboard.php");
    exit();
}

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    // Check admin table (not the regular user table)
    $sql    = "SELECT * FROM tblAdmin WHERE username = '$username' AND email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Correct credentials - log admin in
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $username;

        header("Location: admin/dashboard.php");
        exit();
    } else {
        $errorMessage = "Wrong admin credentials. Please try again.";
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 440px;">
    <div class="card">
        <h2 class="page-title">🔐 Admin Login</h2>

        <?php if ($errorMessage != ""): ?>
            <div class="alert alert-error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form method="POST" action="admin_login.php">
            <div class="form-group">
                <label>Admin Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Admin Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-red" style="width:100%;">Login as Admin</button>
        </form>

        <p style="text-align:center; margin-top:16px;">
            <a href="login.php">← Back to User Login</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
