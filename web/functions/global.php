<?php
// Error Check
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Required Classes
require_once '../classes/class_database.php';

// Start the session
session_start();

# ============================================================================
# Database Connection
# ============================================================================
function dbconnect() {
  // Database connection.
  $conn = mysqli_connect("localhost", "root", "", "objectTracker2");

  // display error on fail.
  if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
  }

  // Return connection
  return $conn;
}

# ============================================================================
# Toggle Dark Mode
# ============================================================================
if (isset($_POST['btnDark'])) {

  // Check to see if dark mode is on.
  if ($_SESSION["darkMode"] == 0) {
    // Turn on dark mode.
    $_SESSION["darkMode"] = 1;
  } else {
    // Turn off dark mode.
    $_SESSION["darkMode"] = 0;
  }

  // return to previous page.
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

# ============================================================================
# Import Database
# ============================================================================
if (isset($_POST['btnImportDatabase'])) {
  $far = new Database();

  $far->createDatabase();

  // return to previous page.
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>
