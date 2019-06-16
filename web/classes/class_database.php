<?php
/**
 * Database Class
 */
class Database {
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
  # Database Error
  # ============================================================================
  public function dbError($sql,$conn) {
    if (!$sql) {
      $error = "<div class='text-center alert alert-danger'>ERROR: Could not able to execute $sql. " . mysqli_error($conn) . "</div>";
    }

    return $error;
  }

  # ============================================================================
  # Database Import Database
  # ============================================================================
  public function createDatabase() {
    // Attempt select query execution
    $conn = $this->dbconnect();

    $file = '../sql/objectTracker2.sql';

    if($fp = file_get_contents($file)) {
      $var_array = explode(';',$fp);
      foreach($var_array as $value) {
        $sql = mysqli_query($conn,$value.';');
      }
    }

    if (!$sql) {
      $error = "<div class='text-center alert alert-danger'>ERROR: Could not able to execute $sql. " . mysqli_error($conn) . "</div>";
    }

    return $error;
  }

  # ============================================================================
  # Database Trucate Counter Database Table
  # ============================================================================
  public function truncateCounterTableDatabase() {
    // Attempt select query execution
    $conn = $this->dbconnect();

    $sql = $conn->query("TRUNCATE TABLE counter");

    if (!$sql) {
      $error = "<div class='text-center alert alert-danger'>ERROR: Could not able to execute $sql. " . mysqli_error($conn) . "</div>";
    }

    return $error;
  }

  # ============================================================================
  # Count Tables
  # ============================================================================
  public function countTables() {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Adapt query based on input variable.
    $sql = $conn->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'objectTracker2';");

    if (!$sql) {
      die("Error:" . mysqli_error($conn));
    }

    // Get rows
    $row = $sql->fetch_row();
    $count = $row[0];

    // Close connection
    mysqli_close($conn);

    return $count;
  }

  # ============================================================================
  # List Tables
  # ============================================================================
  public function listTables() {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Adapt query based on input variable.
    $sql = $conn->query("SELECT `TABLE_NAME`, `TABLE_ROWS` FROM information_schema.tables WHERE table_schema = 'objectTracker2';");

    if (!$sql) {
      die("Error:" . mysqli_error($conn));
    }

    // Get rows
    // $row = $sql->fetch_row();

    while($row = mysqli_fetch_array($sql)) {
      $newArray[] = [$row['TABLE_NAME'], $row['TABLE_ROWS']];
    }

    mysqli_free_result($sql);

    // Close connection
    mysqli_close($conn);

    return $newArray;
  }

  # ============================================================================
  # Get Table List
  # ============================================================================
  public function getTableList() {
    $conn = $this->dbconnect();

    $myArray = $this->listTables();

    echo "<table class='table table-sm'>";
      for ($i=0; $i < count($myArray); $i++) {
        echo "<tr>";
          echo "<td>{$myArray[$i][0]}</td>";
          echo "<td class='text-center'>{$myArray[$i][1]}</td>";
        echo "</tr>";
      }
    echo "</table>";

  }

}

?>
