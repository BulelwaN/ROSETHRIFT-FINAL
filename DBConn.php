<?php
// =============================================================================
// DBConn.php  —  Database Connection File
// -----------------------------------------------------------------------------
// PURPOSE:
//   This file does two important things every time it is included:
//     1. Starts the PHP session (so we can remember who is logged in)
//     2. Opens a connection to the ClothingStore MySQL database
//
// HOW TO USE:
//   Add this line at the top of any PHP file that needs the database:
//       include 'DBConn.php';         (from the root folder)
//       include '../DBConn.php';      (from a sub-folder)
//   After including it, use $conn to run your queries.
//
// REQUIREMENT (Part 2 - Section 2):
//   Connection code saved in DBConn.php using MySQLi (improved MySQL interface)
// =============================================================================


// -----------------------------------------------------------------------------
// SESSION
// Start the PHP session — this allows us to store login information
// (like user_id and user_name) across multiple pages.
// We check session_status() first to avoid a "session already started" warning
// in case DBConn.php gets included more than once on the same page.
// -----------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// -----------------------------------------------------------------------------
// DATABASE SETTINGS
// Change these four values to match your own XAMPP / local server setup.
//   $host     → usually "localhost" for XAMPP
//   $username → usually "root" for XAMPP (no password by default)
//   $password → leave as "" for XAMPP unless you manually set one
//   $database → must match the database name you created in phpMyAdmin
// -----------------------------------------------------------------------------
$host     = "localhost";     // server address — localhost means this same machine
$username = "root";          // MySQL username for XAMPP
$password = "";              // MySQL password — blank by default in XAMPP
$database = "ClothingStore"; // database name — must exist in phpMyAdmin


// -----------------------------------------------------------------------------
// CONNECT TO THE DATABASE  (MySQLi — improved MySQL interface)
// mysqli_connect() tries to open a connection using the settings above.
// If successful it returns a connection object stored in $conn.
// Every other PHP file uses $conn to run SQL queries against the database.
// -----------------------------------------------------------------------------
$conn = mysqli_connect($host, $username, $password, $database);


// -----------------------------------------------------------------------------
// CONNECTION CHECK
// If $conn is false the connection failed (wrong credentials, DB missing, etc.)
// die() stops the script immediately and prints a helpful error message.
// mysqli_connect_error() explains exactly why the connection failed.
// -----------------------------------------------------------------------------
if (!$conn) {
    die("
        <p style='color:red; font-family:Arial;'>
            <strong>Database connection failed.</strong><br>
            Error: " . mysqli_connect_error() . "<br><br>
            Please check:<br>
            &bull; XAMPP is running (Apache + MySQL both green)<br>
            &bull; The database <em>ClothingStore</em> exists in phpMyAdmin<br>
            &bull; The username/password in DBConn.php are correct
        </p>
    ");
}
?>
