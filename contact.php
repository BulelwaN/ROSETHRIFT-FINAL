<?php

// contact.php - Message the Admin
// Users can send messages to the admin and view their past messages
$pageTitle = "Messages";
$cssPath   = "";
$rootPath  = "";

include 'includes/DBConn.php';

// Only logged-in users can message
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: login.php");
    exit();
}

$successMessage = "";
$errorMessage   = "";
$userID = $_SESSION['user_id'];

// Send a new message
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $body    = mysqli_real_escape_string($conn, $_POST['body']);

    $sql = "INSERT INTO tblMessages (senderID, subject, body) VALUES ('$userID', '$subject', '$body')";

    if (mysqli_query($conn, $sql)) {
        $successMessage = "Your message was sent to the admin!";
    } else {
        $errorMessage = "Could not send message. Try again.";
    }
}

// Get all messages this user has sent
$msgSQL = "SELECT * FROM tblMessages WHERE senderID = '$userID' ORDER BY created_at DESC";
$msgs   = mysqli_query($conn, $msgSQL);

include 'includes/header.php';
?>

<div class="container" style="max-width:700px;">

    <h2 class="page-title">💬 Messages</h2>

    <!-- ===== SEND A MESSAGE ===== -->
    <div class="card">
        <h3 style="margin-bottom:16px;">Send Message to Admin</h3>

        <?php if ($successMessage != ""): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <?php if ($errorMessage != ""): ?>
            <div class="alert alert-error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form method="POST" action="contact.php">
            <div class="form-group">
                <label>Subject:</label>
                <input type="text" name="subject" placeholder="e.g. Question about my order" required>
            </div>
            <div class="form-group">
                <label>Message:</label>
                <textarea name="body" rows="4" placeholder="Type your message here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-red">Send Message</button>
        </form>
    </div>

    <!-- ===== MY MESSAGES LIST ===== -->
    <div class="card">
        <h3 style="margin-bottom:16px;">My Messages</h3>

        <?php if (mysqli_num_rows($msgs) > 0): ?>
            <?php while ($m = mysqli_fetch_assoc($msgs)): ?>
                <div style="border:1px solid #eee; border-radius:8px; padding:16px; margin-bottom:14px; border-left: 4px solid <?php echo $m['replied'] ? '#27ae60' : '#e67e22'; ?>;">
                    <strong><?php echo $m['subject']; ?></strong>
                    <span style="float:right; font-size:0.85rem; color:#888;">
                        <?php echo date('d M Y', strtotime($m['created_at'])); ?>
                    </span>
                    <p style="margin:8px 0; color:#555;"><?php echo $m['body']; ?></p>

                    <?php if ($m['replied']): ?>
                        <div style="background:#d4edda; padding:10px; border-radius:6px; margin-top:8px;">
                            <strong>Admin reply:</strong> <?php echo $m['reply']; ?>
                        </div>
                    <?php else: ?>
                        <span class="badge badge-yellow">Awaiting reply</span>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No messages sent yet.</p>
        <?php endif; ?>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
