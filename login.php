<?php
// =============================================================================
// login.php  —  Customer Login Page
// -----------------------------------------------------------------------------
// PURPOSE:
//   Allows registered customers to log in to Rosethrift.
//   This page:
//     1. Shows a login form (username, email, password)
//     2. When the form is submitted, it looks the user up in tblUser
//     3. Checks the entered password matches the stored MD5 hash
//     4. Checks the account has been verified by an admin
//     5. On success  → saves login info in the session and goes to dashboard
//     6. On failure  → shows an error and keeps the form sticky (fields pre-filled)
//
// REQUIREMENT (Part 2 - Section 5):
//   - Accept username and email address
//   - Compare entered password against stored hash in tblUser
//   - Use HTML5 validation on form submission (required attributes)
//   - Valid login   → display user data in table using associative array
//   - Invalid login → sticky form retains entered details
// =============================================================================


// Page settings used by header.php
$pageTitle = "Login";
$cssPath   = "";
$rootPath  = "";

// Include the database connection — gives us $conn and starts the session
include 'includes/DBConn.php';


// -----------------------------------------------------------------------------
// REDIRECT IF ALREADY LOGGED IN
// If the user already has an active session, skip the login form and send
// them straight to their dashboard so they do not have to log in again.
// -----------------------------------------------------------------------------
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header("Location: customer_dashboard.php");
    exit();
}


// -----------------------------------------------------------------------------
// INITIALISE VARIABLES
// Set default empty values for the error message and sticky form fields.
// These are used in the HTML below — empty strings mean nothing is shown yet.
// -----------------------------------------------------------------------------
$errorMessage   = "";     // holds any error message to display to the user
$stickyUsername = "";     // pre-fills the username input on failed login
$stickyEmail    = "";     // pre-fills the email input on failed login


// -----------------------------------------------------------------------------
// FORM SUBMISSION HANDLING
// This block only runs when the user clicks the Login button (POST request).
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ---- Get and sanitise the submitted values ----
    // mysqli_real_escape_string() prevents SQL injection by escaping special characters
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);

    // Hash the entered password using MD5 so we can compare it to the stored hash
    // (The database never stores plain-text passwords)
    $password = md5($_POST['password']);

    // ---- Sticky form values ----
    // Save the raw (unsanitised) typed values so we can put them back in the
    // form inputs if login fails — this saves the user having to retype everything.
    // htmlspecialchars() prevents XSS by converting < > & " ' to safe HTML entities.
    $stickyUsername = htmlspecialchars($_POST['username']);
    $stickyEmail    = htmlspecialchars($_POST['email']);

    // ---- Look up the username in tblUser ----
    // We search by username only first, then verify email and password separately
    // so we can give specific error messages for each wrong field.
    $sql    = "SELECT * FROM tblUser WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        // Username was found — fetch all the user's data as an associative array
        // (associative array = column names are used as the array keys)
        $user = mysqli_fetch_assoc($result);

        // ---- Check 1: Does the email match? ----
        if ($user['email'] != $email) {
            $errorMessage = "Wrong email address.";

        // ---- Check 2: Does the password hash match? ----
        } elseif ($user['password'] != $password) {
            $errorMessage = "Wrong password.";

        // ---- Check 3: Has an admin verified this account? ----
        // New registrations start as 'Pending' and cannot log in until
        // an admin changes their status to 'Verified'
        } elseif ($user['status'] != 'Verified') {
            $errorMessage = "Your account is waiting for admin approval.";

        } else {
            // ---- All checks passed — log the user in ----
            // Save their key details in the PHP session so every other page
            // knows who is logged in without querying the database again.
            $_SESSION['logged_in']  = true;
            $_SESSION['user_id']    = $user['userID'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['username']   = $user['username'];
            $_SESSION['user_type']  = $user['userType'];

            // Send the user to their personal dashboard
            header("Location: customer_dashboard.php");
            exit();
        }

    } else {
        // No user was found with that username
        $errorMessage = "Username not found.";
    }
}

// Load the shared navigation header (html, head, nav bar)
include 'includes/header.php';
?>

<!-- ============================================================
     LOGIN FORM HTML
     ============================================================ -->
<div class="container" style="max-width: 480px;">
    <div class="card">
        <h2 class="page-title">User Login</h2>

        <!-- Error message — only shown when $errorMessage is not empty -->
        <?php if ($errorMessage != ""): ?>
            <div class="alert alert-error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!--
            STICKY FORM:
            The value="" attributes are pre-filled with $stickyUsername and $stickyEmail.
            On a failed login these hold what the user typed so they do not
            have to retype their username and email — only fix the wrong field.
            The password field is intentionally left blank for security.
        -->
        <form method="POST" action="login.php">

            <div class="form-group">
                <label>Username:</label>
                <!-- HTML5 required attribute = browser will block submit if left empty -->
                <input type="text"
                       name="username"
                       placeholder="Enter your username"
                       value="<?php echo $stickyUsername; ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Email Address:</label>
                <!-- type="email" = HTML5 validation ensures a valid email format -->
                <input type="email"
                       name="email"
                       placeholder="Enter your email"
                       value="<?php echo $stickyEmail; ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <!-- Password field is never sticky — left blank after a failed login -->
                <input type="password"
                       name="password"
                       placeholder="Enter your password"
                       required>
            </div>

            <button type="submit" class="btn btn-red" style="width:100%;">Login</button>
        </form>

        <p style="text-align:center; margin-top:16px;">
            No account yet? <a href="register.php">Register here</a>
        </p>
        <p style="text-align:center;">
            <a href="admin_login.php">Admin Login →</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
