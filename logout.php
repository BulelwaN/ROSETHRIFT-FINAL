<?php

// logout.php - Logs the user out
// Clears the session and redirects to homepage


include 'includes/DBConn.php';

// Clear all session data
session_unset();
session_destroy();

// Go back to homepage
header("Location: index.php");
exit();
?>
