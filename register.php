<?php
// =============================================================================
// register.php  —  New User Registration Page
// -----------------------------------------------------------------------------
// PURPOSE:
//   Allows new visitors to create a Rosethrift account.
//   This page:
//     1. Shows a registration form (name, email, phone, username, password, role)
//     2. When submitted, checks the username and email are not already taken
//     3. Hashes the password using MD5 before saving it (never store plain text)
//     4. Inserts the new user into tblUser with status = 'Pending'
//     5. The user CANNOT log in until an administrator verifies their account
//
// REQUIREMENT (Part 2 - Section 6):
//   - New users can register and create a password hash
//   - Registration is Pending by default
//   - Login is not allowed until an Administrator verifies the user
// =============================================================================


// Page settings used by header.php
$pageTitle = "Register";
$cssPath   = "";
$rootPath  = "";

// Include the database connection — gives us $conn and starts the session
include 'includes/DBConn.php';

// Initialise message variables — empty until the form is submitted
$successMessage = "";
$errorMessage   = "";


// -----------------------------------------------------------------------------
// FORM SUBMISSION HANDLING
// Only runs when the user clicks the "Create Account" button (POST request)
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ---- Get and sanitise all form values ----
    // mysqli_real_escape_string() prevents SQL injection attacks
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $surname  = mysqli_real_escape_string($conn, $_POST['surname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role     = $_POST['role']; // 'buyer' or 'seller' — comes from the dropdown

    // ---- Hash the password with MD5 ----
    // We never store the plain-text password.
    // MD5 converts it to a 32-character hex string (e.g. "482c811da5d5b4bc...")
    // When the user logs in, we MD5 their attempt and compare the two hashes.
    $password = md5($_POST['password']);

    // ---- Check for duplicate username or email ----
    // We use OR so we catch both cases in a single query.
    // If any row is returned, the username or email is already registered.
    $checkSQL = "SELECT userID FROM tblUser WHERE username = '$username' OR email = '$email'";
    $check    = mysqli_query($conn, $checkSQL);

    if (mysqli_num_rows($check) > 0) {
        // Duplicate found — tell the user which one is taken
        $errorMessage = "That username or email is already registered. Please choose a different one.";

    } else {

        // ---- Insert the new user into tblUser ----
        // status is set to 'Pending' — the user CANNOT log in until an admin
        // visits the admin panel and changes their status to 'Verified'.
        $insertSQL = "
            INSERT INTO tblUser (name, surname, email, phone, username, password, userType, status)
            VALUES ('$name', '$surname', '$email', '$phone', '$username', '$password', '$role', 'Pending')
        ";

        if (mysqli_query($conn, $insertSQL)) {
            // Registration successful — inform the user and ask them to wait
            $successMessage = "Registration successful! Please wait for an administrator to approve your account before logging in.";
        } else {
            // MySQL returned an error — something unexpected went wrong
            $errorMessage = "Something went wrong. Please try again. Error: " . mysqli_error($conn);
        }
    }
}

// Load the shared navigation header
include 'includes/header.php';
?>

<!-- ============================================================
     REGISTRATION FORM HTML
     ============================================================ -->
<div class="container" style="max-width: 560px;">
    <div class="card">
        <h2 class="page-title">Create an Account</h2>

        <!-- Success message — shown after a successful registration -->
        <?php if ($successMessage != ""): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- Error message — shown if validation fails or duplicate detected -->
        <?php if ($errorMessage != ""): ?>
            <div class="alert alert-error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- All inputs use required for HTML5 browser-side validation -->
        <form method="POST" action="register.php">

            <!-- First name and surname side by side using two-column grid -->
            <div class="form-two-col">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="name" placeholder="e.g. Lerato" required>
                </div>
                <div class="form-group">
                    <label>Surname *</label>
                    <input type="text" name="surname" placeholder="e.g. Mokoena" required>
                </div>
            </div>

            <!-- Email and phone side by side -->
            <div class="form-two-col">
                <div class="form-group">
                    <label>Email Address *</label>
                    <!-- type="email" enforces a valid email format via HTML5 -->
                    <input type="email" name="email" placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" placeholder="e.g. 072 123 4567" required>
                </div>
            </div>

            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" placeholder="Choose a username" required>
            </div>

            <div class="form-group">
                <label>Password *</label>
                <!-- type="password" hides the characters as the user types -->
                <input type="password" name="password" placeholder="Choose a password" required>
            </div>

            <!-- Role dropdown — determines whether the user is a buyer or seller -->
            <div class="form-group">
                <label>I want to:</label>
                <select name="role" required>
                    <option value="buyer">Buy clothing</option>
                    <option value="seller">Buy and Sell clothing</option>
                </select>
            </div>

            <button type="submit" class="btn btn-red" style="width:100%;">Create Account</button>
        </form>

        <p style="text-align:center; margin-top:16px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
