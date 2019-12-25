<?php
require_once 'class_objectDetection.php';
require_once 'common.php';

/**
 * Report Generator class
 */
class reports {

  private $lastTrigger; //time of last Trigger

  # ============================================================================
  # Constructor
  # Contrusts a report with details on how to pull information from the database
  # Detials:
  # - Trigger
  # - Process
  # - Conditional
  # - Action
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

  public function assessTrigger() {
    // does what is says on the tin
    if(true) return $this->process();
  }

  private function process() {
    // requres deatils on how to parse an SQL search
    // needs a table, column, count bool?, WHERE condition is

    $sql = "SELECT";

    if($countBool) {
      $sql += " COUNT('$column')";
    } else {
      $sql += " '$column'";
    }

    $sql +=  " FROM '$table' WHERE '$column' '$comparitor' '$value'";

    echo $sql;
  }


  # ============================================================================
  # Get CSV
  # ============================================================================
  public function getCSV() {
    echo "Get CSV Pressed!";
  }
}

?>
