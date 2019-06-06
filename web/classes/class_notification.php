<?php
require_once 'class_objectDetection.php';
require_once 'common.php';

/**
 * Notification class
 */
class notification {
  public $deviceID;
  public $deviceName;
  public $deviceLocation;
  public $deviceConfidence;
  public $deviceClientVersion;

  # ============================================================================
  # Constructor
  # ============================================================================
  function __construct(){}

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
  # Count Notifications
  # ============================================================================
  public function countNotifications($input) {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Adapt query based on input variable.
    if ($input == "All") {
      $sql = $conn->query("SELECT COUNT(DISTINCT `notification_id`) FROM `notifications`");
    } else {
      $sql = $conn->query("SELECT COUNT(DISTINCT $input) FROM `notifications`");
    }

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
  # Empty Client Version
  # ============================================================================
  // public function emptyClientVersion($clientVersion) {
  //   if (empty($clientVersion)) {
  //     $clientVersion = $row['device_clientVersion'];
  //   } else {
  //     // Client Version not seen.
  //     $clientVersion = "Unknown";
  //   }
  // }

  # ============================================================================
  # Select All Devices
  # ============================================================================
  public function selectAllNotifications() {
    // Connect to the database.
    $conn = $this->dbconnect();

    $result = mysqli_query($conn, "SELECT * FROM `notification` INNER JOIN devices ON notification.notification_deviceID = devices.device_id");

    if (!$result) {
      echo("Error description: " . mysqli_error($con));
    }

    // If results is true.
    if($result) {
      $headers = array("Priority","Device","Category","Time","Message");
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm'>";
                echo "<tr>";
                  echo "<th class='text-center'><input id='noteSelectAll' class='noteCheckbox' type='checkbox'></th>";
                  for ($i=0; $i < count($headers); $i++) {
                    echo "<th class='text-center'>{$headers[$i]}</th>";
                  }
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)) {
              // Variables
              $notificationID = $row['notification_id'];
              $notificationDeviceID = $row['device_name'];
              $notificationCategory= $row['notification_category'];
              $notificationDatetime = $row['notification_datetime'];
              $notificationMessage = $row['notification_message'];
              $notificationPriority = $row['notification_priority'];

              // Generate Table Rows.
              echo "<tr>";
                echo "<td class='text-center'><input class='doCheckbox' type='checkbox' value='{$notificationID}'></td>";
                echo "<td class='text-center'>".getPriority($notificationPriority)."</td>";
                echo "<td class='text-center'>{$notificationDeviceID}</td>";
                echo "<td class='text-center'>".getCategory($notificationCategory)."</td>";
                echo "<td>" . date("H:i:s d/m/y", strtotime($notificationDatetime)) . "</td>";
                echo "<td>{$notificationMessage}</td>";
              echo "</tr>";
            }
            echo "</table>";
            // Free result set
            mysqli_free_result($result);
        } else {
            echo "No records matching your query were found.";
        }
    } else {
      echo $this->dbError($sql,$conn);
    }

    // Close connection
    mysqli_close($conn);
  }

  # ============================================================================
  # Stats: All Template
  # ============================================================================
  public function countThings($input) {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Adapt query based on input variable.
    if ($input == "All") {
      $sql = $conn->query("SELECT COUNT(DISTINCT `notification_id`) FROM `notification` LIMIT 5");
    } else {
      $sql = $conn->query("SELECT COUNT(DISTINCT $input) FROM `notification` LIMIT 5");
    }

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
}
?>
