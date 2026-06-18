<?php

// Admin can verify, edit or delete users here


$pageTitle = "Manage Users";
$cssPath   = "../";
$rootPath  = "../";

include '../includes/DBConn.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin_login.php");
    exit();
}

$message = "";

// VERIFY a user (change status to Verified)
if (isset($_GET['verify'])) {
    $id  = $_GET['verify'];
    $sql = "UPDATE tblUser SET status = 'Verified' WHERE userID = '$id'";
    mysqli_query($conn, $sql);
    $message = "User has been verified!";
}

// DELETE a user
if (isset($_GET['delete'])) {
    $id  = $_GET['delete'];
    $sql = "DELETE FROM tblUser WHERE userID = '$id'";
    mysqli_query($conn, $sql);
    $message = "User deleted.";
}

// Get all users
$usersSQL    = "SELECT * FROM tblUser ORDER BY userID DESC";
$usersResult = mysqli_query($conn, $usersSQL);

include '../includes/header.php';
?>

<div class="container">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:16px;">
        <h2 class="page-title" style="margin:0;">👥 Manage Users</h2>
        <a href="addUser.php" class="btn btn-green">➕ Add New User</a>
    </div>

    <?php if ($message != ""): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="card table-box">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
            <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
            <tr>
                <td><?php echo $user['userID']; ?></td>
                <td><?php echo $user['name'] . " " . $user['surname']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['userType']; ?></td>
                <td>
                    <?php if ($user['status'] == 'Verified'): ?>
                        <span class="badge badge-green">Verified</span>
                    <?php else: ?>
                        <span class="badge badge-yellow">Pending</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                <td>
                    <?php if ($user['status'] == 'Pending'): ?>
                        <a href="?verify=<?php echo $user['userID']; ?>" class="btn btn-green btn-small">Verify</a>
                    <?php endif; ?>
                    <a href="editUser.php?id=<?php echo $user['userID']; ?>" class="btn btn-blue btn-small">Edit</a>
                    <a href="?delete=<?php echo $user['userID']; ?>" class="btn btn-grey btn-small"
                       onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <a href="dashboard.php" class="btn btn-grey" style="margin-top:14px;">← Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>
