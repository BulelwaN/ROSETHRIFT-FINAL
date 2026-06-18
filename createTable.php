<?php
// =============================================================================
// createTable.php
// -----------------------------------------------------------------------------
// PURPOSE:
//   This script handles the creation and loading of the tblUser table.
//   Every time you run it, it will:
//     1. Check if tblUser already exists in the database
//     2. If it does exist  → DROP (delete) the table completely
//     3. Recreate tblUser with all the correct columns and constraints
//     4. Read user data from userData.txt line by line
//     5. Insert each user record into the freshly created table
//
// HOW TO RUN:
//   Open your browser and go to: http://localhost/rosethrift/createTable.php
//
// REQUIREMENT (Part 2 - Section 3):
//   - Must check if tblUser exists; if so, delete it
//   - Must recreate the table and load data from userData.txt
//   - Must include DBConn.php as an embedded file
// =============================================================================


// -----------------------------------------------------------------------------
// STEP 1: Include the database connection file (DBConn.php)
// This gives us the $conn variable we need to run queries
// DBConn.php also starts the session and connects to ClothingStore
// -----------------------------------------------------------------------------
include 'includes/DBConn.php';


// -----------------------------------------------------------------------------
// STEP 2: Check if tblUser already exists in the database
// We use SHOW TABLES LIKE to search for the table by name.
// If it is found (num_rows > 0) we will drop it before recreating it.
// This ensures we always start with a clean, fresh table.
// -----------------------------------------------------------------------------
$checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'tblUser'");

if (mysqli_num_rows($checkTable) > 0) {
    // tblUser was found — drop it so we can start fresh
    $dropResult = mysqli_query($conn, "DROP TABLE IF EXISTS `tblUser`");

    if ($dropResult) {
        echo "<p style='color:orange;'>⚠️ tblUser already existed — it has been dropped (deleted).</p>";
    } else {
        // Something went wrong trying to drop the table
        die("<p style='color:red;'>❌ Failed to drop tblUser: " . mysqli_error($conn) . "</p>");
    }

} else {
    // tblUser did not exist yet — nothing to drop
    echo "<p style='color:gray;'>ℹ️ tblUser did not exist yet — skipping drop step.</p>";
}


// -----------------------------------------------------------------------------
// STEP 3: Recreate tblUser with all required columns
// Column breakdown:
//   userID     - auto-incrementing primary key (unique ID for each user)
//   name       - the user's first name (cannot be empty)
//   surname    - the user's last name
//   username   - login name, must be unique across all users
//   email      - email address, must also be unique
//   phone      - contact number
//   password   - MD5-hashed password (stored as a 32-character hash string)
//   userType   - either 'buyer' or 'seller'
//   status     - either 'Pending' (new registration) or 'Verified' (admin approved)
//   created_at - automatically records the date and time the account was created
// -----------------------------------------------------------------------------
$createSQL = "
    CREATE TABLE `tblUser` (
        `userID`     INT          NOT NULL AUTO_INCREMENT,
        `name`       VARCHAR(100) NOT NULL,
        `surname`    VARCHAR(100) DEFAULT '',
        `username`   VARCHAR(50)  NOT NULL UNIQUE,
        `email`      VARCHAR(100) NOT NULL UNIQUE,
        `phone`      VARCHAR(20)  DEFAULT '',
        `password`   VARCHAR(255) NOT NULL,
        `userType`   VARCHAR(20)  DEFAULT 'buyer',
        `status`     VARCHAR(20)  DEFAULT 'Pending',
        `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`userID`)
    )
";

$createResult = mysqli_query($conn, $createSQL);

if ($createResult) {
    echo "<p style='color:green;'>✅ tblUser has been created successfully.</p>";
} else {
    // Table creation failed — show the MySQL error and stop
    die("<p style='color:red;'>❌ Failed to create tblUser: " . mysqli_error($conn) . "</p>");
}


// -----------------------------------------------------------------------------
// STEP 4: Open userData.txt and read it line by line
// The text file uses a pipe | as the separator between fields.
// The first line is the header row (column names) — we skip it.
// Each line after that is one user record to insert.
//
// Expected file format (pipe-separated):
//   name|surname|username|email|phone|password|userType|status
//   Lerato|Nxumalo|lerato_n|lerato@example.com|072 111 2233|482c811da5d5b4bc6d497ffa98491e38|buyer|Verified
// -----------------------------------------------------------------------------
$filename = "userData.txt";

// Check the file actually exists before trying to open it
if (!file_exists($filename)) {
    die("<p style='color:red;'>❌ userData.txt not found. Make sure it is in the same folder as this script.</p>");
}

// Open the file for reading ("r" = read-only mode)
$fileHandle = fopen($filename, "r");

if (!$fileHandle) {
    die("<p style='color:red;'>❌ Could not open userData.txt.</p>");
}

// Keep a count of how many rows we insert successfully
$insertCount = 0;

// Track which line we are on (line 1 is the header row)
$lineNumber = 0;

echo "<hr><p><strong>Loading data from userData.txt...</strong></p>";


// -----------------------------------------------------------------------------
// STEP 5: Loop through every line in userData.txt and insert into tblUser
// fgets() reads one line at a time until we reach the end of the file (EOF)
// -----------------------------------------------------------------------------
while (($line = fgets($fileHandle)) !== false) {

    $lineNumber++;

    // Remove any extra whitespace or newline characters from the end of the line
    $line = trim($line);

    // Skip the header line (line 1 contains column names, not data)
    if ($lineNumber === 1) {
        echo "<p style='color:gray;'>↪ Skipping header row.</p>";
        continue;
    }

    // Skip any blank lines in the file
    if (empty($line)) {
        continue;
    }

    // Split the line into individual fields using the pipe | character
    // This gives us an array like: ['Lerato', 'Nxumalo', 'lerato_n', ...]
    $fields = explode("|", $line);

    // Make sure the line has exactly 8 fields — if not, skip it
    if (count($fields) < 8) {
        echo "<p style='color:orange;'>⚠️ Line $lineNumber skipped — expected 8 fields, found " . count($fields) . ".</p>";
        continue;
    }

    // Assign each field to a named variable for clarity
    // trim() removes any accidental spaces around the values
    $name     = mysqli_real_escape_string($conn, trim($fields[0]));
    $surname  = mysqli_real_escape_string($conn, trim($fields[1]));
    $username = mysqli_real_escape_string($conn, trim($fields[2]));
    $email    = mysqli_real_escape_string($conn, trim($fields[3]));
    $phone    = mysqli_real_escape_string($conn, trim($fields[4]));
    $password = mysqli_real_escape_string($conn, trim($fields[5])); // already MD5-hashed in the file
    $userType = mysqli_real_escape_string($conn, trim($fields[6]));
    $status   = mysqli_real_escape_string($conn, trim($fields[7]));

    // Build the INSERT SQL statement to add this user to tblUser
    $insertSQL = "
        INSERT INTO `tblUser`
            (`name`, `surname`, `username`, `email`, `phone`, `password`, `userType`, `status`)
        VALUES
            ('$name', '$surname', '$username', '$email', '$phone', '$password', '$userType', '$status')
    ";

    // Run the INSERT and check if it worked
    if (mysqli_query($conn, $insertSQL)) {
        $insertCount++;
        echo "<p style='color:green; margin:2px 0;'>✅ Inserted: <strong>$name $surname</strong> ($username)</p>";
    } else {
        // Insert failed — show the error but keep going with the next line
        echo "<p style='color:red; margin:2px 0;'>❌ Failed to insert line $lineNumber: " . mysqli_error($conn) . "</p>";
    }
}

// Close the file now that we are done reading it
fclose($fileHandle);


// -----------------------------------------------------------------------------
// STEP 6: Show a final summary of what happened
// -----------------------------------------------------------------------------
echo "<hr>";
echo "<h3>✅ Done! Summary:</h3>";
echo "<ul>";
echo "<li>Table <strong>tblUser</strong> was dropped (if it existed) and recreated.</li>";
echo "<li><strong>$insertCount</strong> user record(s) were loaded from userData.txt.</li>";
echo "</ul>";
echo "<p><a href='login.php'>→ Go to Login Page</a></p>";
?>
