<?php
$pageTitle="Messages"; $cssPath="../"; $rootPath="../";
include '../includes/DBConn.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../admin_login.php"); exit(); }
$success = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mid   = $_POST['messageID'];
    $reply = mysqli_real_escape_string($conn, $_POST['reply']);
    mysqli_query($conn, "UPDATE tblMessages SET reply='$reply', replied=1 WHERE messageID='$mid'");
    $success = "Reply sent!";
}
$msgs = mysqli_query($conn, "SELECT tblMessages.*, tblUser.name AS sender, tblUser.username FROM tblMessages JOIN tblUser ON tblMessages.senderID = tblUser.userID ORDER BY replied ASC, created_at DESC");
include '../includes/header.php';
?>
<div class="container">
  <h2 class="page-title">💬 User Messages</h2>
  <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
  <?php if (mysqli_num_rows($msgs) == 0): ?>
    <div class="card"><p>No messages yet.</p></div>
  <?php else: ?>
    <?php while ($msg = mysqli_fetch_assoc($msgs)): ?>
      <div class="card" style="border-left: 4px solid <?php echo $msg['replied'] ? '#27ae60' : '#e67e22'; ?>; margin-bottom:16px;">
        <div style="display:flex; justify-content:space-between; flex-wrap:wrap; margin-bottom:8px;">
          <strong><?php echo $msg['subject']; ?></strong>
          <span style="color:#888; font-size:0.85rem;">
            From: <?php echo $msg['sender']; ?> (<?php echo $msg['username']; ?>)
            &bull; <?php echo date('d M Y H:i', strtotime($msg['created_at'])); ?>
          </span>
        </div>
        <p style="background:#f9f9f9; padding:10px; border-radius:6px; margin-bottom:10px;">
          <?php echo $msg['body']; ?>
        </p>
        <?php if ($msg['replied']): ?>
          <div style="background:#d4edda; padding:10px; border-radius:6px;">
            <strong>Your reply:</strong> <?php echo $msg['reply']; ?>
          </div>
        <?php else: ?>
          <form method="POST">
            <input type="hidden" name="messageID" value="<?php echo $msg['messageID']; ?>">
            <div class="form-group"><label>Write Reply:</label>
              <textarea name="reply" rows="2" required placeholder="Type your reply..."></textarea>
            </div>
            <button type="submit" class="btn btn-green btn-small">Send Reply</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
  <a href="dashboard.php" class="btn btn-grey">← Dashboard</a>
</div>
<?php include '../includes/footer.php'; ?>
