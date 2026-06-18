<?php
// =============================================================================
// loadClothingStore.php
// -----------------------------------------------------------------------------
// PURPOSE:
//   This script rebuilds the entire ClothingStore database from scratch.
//   It reads the SQL instructions from myClothingStore.sql and runs them
//   against the database. This means:
//     - All existing tables are DROPPED (deleted)
//     - All tables are RECREATED with the correct structure
//     - All sample data (30 rows per table) is RELOADED
//
// HOW TO RUN:
//   Open your browser and go to: http://localhost/rosethrift/loadClothingStore.php
//
// WARNING:
//   Running this script will DELETE all current data and replace it with the
//   sample data from myClothingStore.sql. Do not run it on a live site.
//
// REQUIREMENT (Part 2 - Section 9):
//   - Must create all tables within the ClothingStore database
//   - Must drop all existing tables before recreating them
//   - Must use MySQLi
//   - Must include the connection file (DBConn.php)
//   - Hint: Export your database to an SQL file and incorporate that code here
// =============================================================================


// -----------------------------------------------------------------------------
// STEP 1: Include the database connection file
// DBConn.php connects to ClothingStore using MySQLi and starts the session.
// It gives us the $conn variable used throughout this script.
// -----------------------------------------------------------------------------
include 'includes/DBConn.php';


// -----------------------------------------------------------------------------
// STEP 2: Specify the SQL file to load
// myClothingStore.sql contains:
//   - DROP TABLE statements  (removes old tables)
//   - CREATE TABLE statements (builds new tables with correct structure)
//   - INSERT INTO statements  (loads 30 rows of sample data per table)
// -----------------------------------------------------------------------------
$sqlFile = "myClothingStore.sql";

// Check that the SQL file actually exists in the project folder
if (!file_exists($sqlFile)) {
    die("
        <p style='color:red;'>
            ❌ myClothingStore.sql was not found.<br>
            Make sure it is in the same folder as this script.
        </p>
    ");
}

echo "<h2>🔄 Loading ClothingStore Database...</h2>";
echo "<p>Reading from: <strong>$sqlFile</strong></p><hr>";


// -----------------------------------------------------------------------------
// STEP 3: Read the entire SQL file into a string
// file_get_contents() loads the whole file as one big string of text.
// This string contains all the DROP, CREATE, and INSERT SQL statements.
// -----------------------------------------------------------------------------
$sql = file_get_contents($sqlFile);

if ($sql === false) {
    die("<p style='color:red;'>❌ Could not read myClothingStore.sql.</p>");
}


// -----------------------------------------------------------------------------
// STEP 4: Run all the SQL statements using multi_query
// multi_query() lets us execute many SQL statements at once (separated by ;)
// This is perfect for an exported SQL file which contains many statements.
//
// After calling multi_query we loop through each result set with next_result()
// to clear the internal buffer — this is required, otherwise the next query
// will fail with a "Commands out of sync" error.
// -----------------------------------------------------------------------------
if (mysqli_multi_query($conn, $sql)) {

    // Count how many result sets (SQL statements) were processed
    $statementCount = 0;

    // Loop through all the result sets returned by multi_query
    do {
        $statementCount++;

        // Free each result set from memory to avoid "out of sync" errors
        $result = mysqli_store_result($conn);
        if ($result) {
            mysqli_free_result($result);
        }

    } while (mysqli_next_result($conn)); // move on to the next SQL statement

    echo "<p style='color:green;'>✅ All SQL statements executed successfully. ($statementCount statements processed)</p>";

} else {
    // multi_query itself failed before running anything
    echo "<p style='color:red;'>❌ Error running SQL: " . mysqli_error($conn) . "</p>";
}


// -----------------------------------------------------------------------------
// STEP 5: Verify the tables were created by listing what now exists
// SHOW TABLES returns all tables currently in the ClothingStore database.
// We display them so you can confirm everything was created correctly.
// -----------------------------------------------------------------------------
echo "<hr><h3>📋 Tables now in ClothingStore database:</h3><ul>";

// Wait briefly for multi_query to fully finish (avoids "out of sync" on next query)
while (mysqli_next_result($conn)) { /* drain any remaining result sets */ }

$tablesResult = mysqli_query($conn, "SHOW TABLES");

if ($tablesResult) {
    while ($row = mysqli_fetch_row($tablesResult)) {
        // Each row has one value: the table name
        echo "<li>✅ " . htmlspecialchars($row[0]) . "</li>";
    }
} else {
    echo "<li style='color:red;'>Could not retrieve table list: " . mysqli_error($conn) . "</li>";
}

echo "</ul>";


// -----------------------------------------------------------------------------
// STEP 6: Show a final completion message with navigation links
// -----------------------------------------------------------------------------
echo "<hr>";
echo "<h3>✅ Database reload complete!</h3>";
echo "<p>The ClothingStore database has been fully rebuilt from myClothingStore.sql.</p>";
echo "<p>";
echo "<a href='login.php'>→ Go to Login Page</a> &nbsp;|&nbsp; ";
echo "<a href='createTable.php'>→ Run createTable.php</a>";
echo "</p>";
?>
