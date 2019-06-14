<?php
require_once 'class_objectDetection.php';
require_once 'common.php';

/**
 * Dashboard class
 */
class devices {
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
  # Count Devices
  # ============================================================================
  public function countDevices($input) {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Adapt query based on input variable.
    if ($input == "All") {
      $sql = $conn->query("SELECT COUNT(DISTINCT `device_id`) FROM `devices`");
    } else {
      $sql = $conn->query("SELECT COUNT(DISTINCT $input) FROM `devices`");
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
  public function emptyClientVersion($clientVersion) {
    if (empty($clientVersion)) {
      $clientVersion = $clientVersion;
    } else {
      // Client Version not seen.
      $clientVersion = "Unknown";
    }
  }

  # ============================================================================
  # Count Device Rows
  # ============================================================================
  public function countDeviceRows($deviceID) {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT COUNT(`count_id`) FROM `counter` WHERE `count_deviceID` = $deviceID ORDER BY `count_id` DESC LIMIT 1");
    $row = $sql->fetch_row();
    $count = $row[0];

    //free memory associated with result
    $sql->close();

    // Close connection
    mysqli_close($conn);

    return $count;
  }

  # ============================================================================
  # Count Device Rows
  # ============================================================================
  public function countDeviceRowsTotal($deviceID) {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT COUNT(`count_id`) FROM `counter` ORDER BY `count_id` DESC LIMIT 1");
    $row = $sql->fetch_row();
    $count = $row[0];

    //free memory associated with result
    $sql->close();

    // Close connection
    mysqli_close($conn);

    return $count;
  }

  # ============================================================================
  # Count Last Time Device Seen
  # ============================================================================
  public function countDeviceLastTime($deviceID) {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT MAX(`count_time`) FROM `counter` WHERE `count_deviceID` = $deviceID LIMIT 1");
    $row = $sql->fetch_row();
    $count = $row[0];

    //free memory associated with result
    $sql->close();

    // Close connection
    mysqli_close($conn);

    return $count;
  }

  # ============================================================================
  # Count Last Device GPS
  # ============================================================================
  public function countDeviceLastGPS($deviceID) {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT `count_id`, `count_lat`, `count_long` FROM `counter` WHERE `count_deviceID` = $deviceID ORDER BY `count_id` DESC LIMIT 1");
    $row = $sql->fetch_row();
    $count = array($row[1],$row[2]);

    //free memory associated with result
    $sql->close();

    // Close connection
    mysqli_close($conn);

    return $count;
  }

  # ============================================================================
  # Select All Devices
  # ============================================================================
  public function selectAllDevices() {
    // Connect to the database.
    $conn = $this->dbconnect();

    $result = mysqli_query($conn, "SELECT * FROM devices");

    if (!$result) {
      echo("Error description: " . mysqli_error($con));
    }

    // If results is true.
    if($result) {
      $headers = array("Name","Location","IP","Last Seen","# Records","Conf. Threshold","Ver.","GPS");
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm table-bordered'>";
                echo "<tr>";
                  for ($i=0; $i < count($headers); $i++) {
                    echo "<th onclick='sortTable($i)' class='text-center'>{$headers[$i]}</th>";
                  }
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)) {

                // Map variables.
                $deviceID = $row['device_id'];
                $deviceName = $row['device_name'];
                $deviceLocation = $row['device_location'];
                $deviceIP = $row['device_ip'];
                $clientVersion = $row['device_clientVersion'];
                $deviceConfidence = $row['device_confidenceThreshold'];
                $deviceNumRecords = $this->countDeviceRows($deviceID);
                $deviceLastSeen = date("H:i:s d/m/y", strtotime($this->countDeviceLastTime($deviceID)));
                $this->emptyClientVersion($clientVersion);

                $deviceLastSeen = ifNoDate($deviceLastSeen);

                $lat = $this->countDeviceLastGPS($deviceID)[0];
                $long = $this->countDeviceLastGPS($deviceID)[1];
                $latLong = $lat . " " . $long;

                // If there is no records
                if (empty($deviceNumRecords) || $deviceNumRecords == 0) {
                  $deviceNumRecords = 'N/A';
                } else {
                  $deviceNumRecords = $deviceNumRecords . "/" . $this->countDeviceRowsTotal($deviceID);;
                }

                // If blank
                if ($deviceName == "") {
                  $deviceName = "N/A";
                }

                // Generate Table Rows.
                echo "<tr>";
                    echo "<td>{$deviceName}</td>";
                    echo "<td class='text-center'>{$deviceLocation}</td>";
                    echo "<td class='text-center'>{$deviceIP}</td>";
                    echo "<td class='text-center'>{$deviceLastSeen}</td>";
                    echo "<td class='text-center'>{$deviceNumRecords}</td>";
                    echo "<td class='text-center ".formatConfidenceColours($deviceConfidence)."'>".formatConfidence($deviceConfidence)."</td>";
                    echo "<td class='text-center' title='{$clientVersion}'><i class='fas fa-code-branch'></i></td>";

                    // Check if there is a GPS coord.
                    if (empty($lat) || empty($long)) {
                      echo "<td class='text-center'></td>";
                    } else {
                      echo "<td class='text-center'><i class='fas fa-globe-europe' title='{$latLong}'></i></td>";
                    }

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
  # Select All Devices CRUD
  # ============================================================================
  public function selectAllDevicesCRUD() {
    // Connect to the database.
    $conn = $this->dbconnect();
    $result = mysqli_query($conn, "SELECT * FROM devices");

    if (!$result) {
      echo("Error description: " . mysqli_error($con));
    }

    // If results is true.
    if($result) {
      $headers = array("ID","Name","Location","IP","Last Seen","Last Ping","# Records","Conf. Threshold","Ver.","Mission","GPS","Ping","Save");
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm table-bordered'>";
                echo "<tr>";
                  for ($i=0; $i < count($headers); $i++) {
                    echo "<th onclick='sortTable($i)' class='text-center'>{$headers[$i]}</th>";
                  }
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)) {
              // Map variables.
              $deviceID = $row['device_id'];
              $deviceName = $row['device_name'];
              $deviceIP = $row['device_ip'];
              $clientVersion = $row['device_clientVersion'];
              $deviceLocation = $row['device_location'];
              $deviceNumRecords = $this->countDeviceRows($deviceID);
              $deviceConfidence = $row['device_confidenceThreshold'];
              $deviceClientVersion = $row['device_clientVersion'];
              $deviceLastSeen = date("H:i:s d/m/y", strtotime($this->countDeviceLastTime($deviceID)));
              $deviceLastPing = date("H:i:s d/m/y", strtotime($row['device_lastPing']));
              $this->emptyClientVersion($deviceClientVersion);

              // If there is no records
              if (empty($deviceNumRecords) || $deviceNumRecords == 0) {
                $deviceNumRecords = 'N/A';
              } else {
                $deviceNumRecords = $deviceNumRecords . "/" . $this->countDeviceRowsTotal($deviceID);;
              }

              // Check if there is a date.
              $deviceLastSeen = ifNoDate($deviceLastSeen);
              $deviceLastPing = ifNoDate($deviceLastPing);

              $lat = $this->countDeviceLastGPS($deviceID)[0];
              $long = $this->countDeviceLastGPS($deviceID)[1];
              $latLong = $lat . " " . $long;

              // If blank
              if ($deviceName == "") {
                $deviceName = "N/A";
              }

              // Generate Table Rows.
              echo "<tr>";
                echo "<td class='text-center'>{$deviceID}</td>";
                echo "<td>{$deviceName}</td>";
                echo "<td class='text-center'>{$deviceLocation}</td>";
                echo "<td class='text-center'>{$deviceIP}</td>";
                echo "<td class='text-center'>{$deviceLastSeen}</td>";
                echo "<td class='text-center'>{$deviceLastPing}</td>";
                echo "<td class='text-center'>{$deviceNumRecords}</td>";
                echo "<td class='text-center'><input name='{$deviceID}' class='text-center' type='text' value='{$deviceConfidence}'></input></td>";
                echo "<td class='text-center'>".listMissions()."</td>";
                echo "<td class='text-center' title='{$clientVersion}'><i class='fas fa-code-branch'></i></td>";

                // Check if there is a GPS coord.
                if (empty($lat) || empty($long)) {
                  echo "<td class='text-center'></td>";
                } else {
                  echo "<td class='text-center'><i class='fas fa-globe-europe' title='{$latLong}'></i></td>";
                }

                // Can Ping?
                // Cannot ping if there is no IP address.
                if (!empty($deviceIP)) {
                  echo "<td class='text-center'><a href='#'><i class='fas fa-broadcast-tower'></i></a></td>";
                } else {
                  echo "<td></td>";
                }

                echo "<td class='text-center'><a href='#'><i class='fas fa-save'></i></a></td>";
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
}
?>
