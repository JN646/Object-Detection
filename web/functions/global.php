<?php
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
?>
