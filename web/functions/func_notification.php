<?php
include '../classes/class_notification.php';

// Error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

# ============================================================================
# Database Connection
# ============================================================================
function dbconnect() {
  $conn = mysqli_connect("localhost", "root", "", "objectTracker2");

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
# Delete ID
# ============================================================================
if (isset($_GET['delete_id'])) {
  // echo "Your ID is... " . $_GET['delete_id'];
  $record = $_GET['delete_id'];

  // Delete record.
  $baz = new notification();
  $baz->deleteNotification($record);

  // Return to page
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

?>
