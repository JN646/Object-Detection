<?php
require_once 'class_objectDetection.php';
require_once 'common.php';

/**
 * Notification class
 */
class notification {
  public $notificationID;
  public $notificationDeviceID;
  public $notificationCategory;
  public $notificationDatetime;
  public $notificationMessage;
  public $notificationPriority;

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
  # Create a notification
  # ============================================================================
  public function createNotification($notificationDeviceID,$notificationCategory,$notificationMessage,$notificationPriority) {
    // Map Variables
    $this->notificationDeviceID = $notificationDeviceID;
    $this->notificationCategory = $notificationCategory;
    $this->notificationMessage = $notificationMessage;
    $this->notificationPriority = $notificationPriority;

    // Attempt select query execution
    $conn = $this->dbconnect();

    // INSERT query.
    $query = "INSERT INTO `notification` (`notification_deviceID`, `notification_category`, `notification_message`, `notification_priority`)
    VALUES ('$notificationDeviceID', '$notificationCategory', '$notificationMessage', '$notificationPriority')";

    // Adapt query based on input variable.
    $sql = $conn->query($query);

    if (!$sql) {
      die("Error:" . mysqli_error($conn));
    }

    // Close connection
    mysqli_close($conn);
  }

  # ============================================================================
  # Delete a notification
  # ============================================================================
  public function deleteNotification($notificationID) {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // INSERT query.
    $query = "DELETE FROM `notification` WHERE `notification_id` = '$notificationID'";

    // Adapt query based on input variable.
    $sql = $conn->query($query);

    if (!$sql) {
      die("Error:" . mysqli_error($conn));
    }

    // Close connection
    mysqli_close($conn);
  }

  # ============================================================================
  # Delete a notification
  # ============================================================================
  public function deleteAllNotification() {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // INSERT query.
    $query = "DELETE FROM `notification` WHERE `notification_id` = '$notificationID'";

    // Adapt query based on input variable.
    $sql = $conn->query($query);

    if (!$sql) {
      die("Error:" . mysqli_error($conn));
    }

    // Close connection
    mysqli_close($conn);
  }

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
      $headers = array("Priority","Device","Category","Time","Message","");
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm table-bordered'>";
                echo "<tr>";
                  echo "<th class='text-center'><input id='noteSelectAll' class='noteCheckbox' type='checkbox'></th>";
                  for ($i=0; $i < count($headers); $i++) {
                    echo "<th class='text-center'>{$headers[$i]}</th>";
                  }
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)) {
              // Variables
              $this->notificationID = $row['notification_id'];
              $this->notificationDeviceID = $row['device_name'];
              $this->notificationCategory = $row['notification_category'];
              $this->notificationDatetime = $row['notification_datetime'];
              $this->notificationMessage = $row['notification_message'];
              $this->notificationPriority = $row['notification_priority'];

              // Generate Table Rows.
              echo "<tr>";
                echo "<td class='text-center'><input class='doCheckbox' type='checkbox' value='{$this->notificationID}'></td>";
                echo "<td class='text-center'>".getPriority($this->notificationPriority)."</td>";
                echo "<td class='text-center'>{$this->notificationDeviceID}</td>";
                echo "<td class='text-center'>".getCategory($this->notificationCategory)."</td>";
                echo "<td>" . date("H:i:s d/m/y", strtotime($this->notificationDatetime)) . "</td>";
                echo "<td>{$this->notificationMessage}</td>";
                echo "<td><div class='dropdown show'>";
                  echo "<a class='btn btn-link dropdown-toggle' href='#' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></a>";
                  echo "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>";
                    echo "<a class='dropdown-item' href='<a href='functions/func_notification.php?delete_id={$notificationID}'>Delete <i class='text-danger fas fa-trash'></i></a>";
                  echo "</div>";
                echo "</div></td>";
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
