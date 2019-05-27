<?php
require_once 'class_objectDetection.php';
require_once 'common.php';

/**
 * Report Generator class
 */
class reports {

  # ============================================================================
  # Constructor
  # ============================================================================
  function __construct() {}

  # ============================================================================
  # Database Connection
  # ============================================================================
  public function dbconnect() {
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
  # Get CSV
  # ============================================================================
  public function getCSV() {
    echo "Get CSV Pressed!";
  }
}

?>
