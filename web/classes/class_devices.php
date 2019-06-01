<?php
require_once 'class_objectDetection.php';

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
  # Empty Client Version
  # ============================================================================
  function emptyClientVersion($clientVersion) {
    if (empty($clientVersion)) {
      $clientVersion = $row['device_clientVersion'];
    } else {
      // Client Version not seen.
      $clientVersion = "Unknown";
    }
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
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm'>";
                echo "<tr>";
                    echo "<th class='text-center'>Name</th>";
                    echo "<th class='text-center'>Location</th>";
                    echo "<th class='text-center'>Last Seen</th>";
                    echo "<th class='text-center'>Conf. Threshold</th>";
                    echo "<th class='text-center'>Ver.</th>";
                    echo "<th class='text-center'>GPS</th>";
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)) {

                // Map variables.
                $deviceID = $row['device_id'];
                $deviceName = $row['device_name'];
                $deviceLocation = $row['device_location'];
                $deviceConfidence = $row['device_confidenceThreshold'];

                emptyClientVersion($clientVersion);

                $deviceLastSeen = date("H:i:s d/m/y", strtotime($this->countDeviceLastTime($deviceID)));

                // If no date.
                if ($deviceLastSeen == "00:00:00 01/01/70" || empty($deviceLastSeen)) {
                  // Never Seen
                  $deviceLastSeen = "Never Seen";
                }

                $lat = $this->countDeviceLastGPS($deviceID)[0];
                $long = $this->countDeviceLastGPS($deviceID)[1];
                $latLong = $lat . " " . $long;

                // If blank
                if ($name == "") {
                  $name = "N/A";
                }

                // Generate Table Rows.
                echo "<tr>";
                    echo "<td>{$deviceName}</td>";
                    echo "<td class='text-center'>{$deviceLocation}</td>";
                    echo "<td class='text-center'>{$deviceLastSeen}</td>";
                    echo "<td class='text-center'>{$deviceConfidence}</td>";
                    echo "<td class='text-center' title='{$clientVesion}'><i class='fas fa-code-branch'></i></td>";

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
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    //free memory associated with result
    $result->close();

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
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm'>";
                echo "<tr>";
                    echo "<th class='text-center'>ID</th>";
                    echo "<th class='text-center'>Name</th>";
                    echo "<th class='text-center'>Location</th>";
                    echo "<th class='text-center'>Last Seen</th>";
                    echo "<th class='text-center'># Records</th>";
                    echo "<th class='text-center'>Conf. Threshold</th>";
                    echo "<th class='text-center'>Ver.</th>";
                    echo "<th class='text-center'>GPS</th>";
                    echo "<th class='text-center'>Edit</th>";
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)) {
                // Map variables.
                $deviceID = $row['device_id'];
                $deviceName = $row['device_name'];
                $deviceLocation = $row['device_location'];
                $deviceNumRecords = '0';
                $deviceConfidence = $row['device_confidenceThreshold'];

                // If there is no records
                if (empty($deviceNumRecords) || $deviceNumRecords == 0) {
                  $deviceNumRecords = 'N/A';
                }

                // emptyClientVersion($clientVersion);

                $deviceLastSeen = date("H:i:s d/m/y", strtotime($this->countDeviceLastTime($deviceID)));

                // If no date.
                if ($deviceLastSeen == "00:00:00 01/01/70" || empty($deviceLastSeen)) {
                  // Never Seen
                  $deviceLastSeen = "Never Seen";
                }

                $lat = $this->countDeviceLastGPS($deviceID)[0];
                $long = $this->countDeviceLastGPS($deviceID)[1];
                $latLong = $lat . " " . $long;

                // If blank
                if ($name == "") {
                  $name = "N/A";
                }

                // Generate Table Rows.
                echo "<tr>";
                    echo "<td>{$deviceID}</td>";
                    echo "<td>{$deviceName}</td>";
                    echo "<td class='text-center'>{$deviceLocation}</td>";
                    echo "<td class='text-center'>{$deviceLastSeen}</td>";
                    echo "<td class='text-center'>{$deviceNumRecords}</td>";
                    echo "<td class='text-center'>{$deviceConfidence}</td>";
                    echo "<td class='text-center' title='{$clientVesion}'><i class='fas fa-code-branch'></i></td>";

                    // Check if there is a GPS coord.
                    if (empty($lat) || empty($long)) {
                      echo "<td class='text-center'></td>";
                    } else {
                      echo "<td class='text-center'><i class='fas fa-globe-europe' title='{$latLong}'></i></td>";
                    }

                    echo "<td class='text-center'><a href='#'><i class='fas fa-edit'></i></a></td>";

                echo "</tr>";
            }
            echo "</table>";
            // Free result set
            mysqli_free_result($result);
        } else {
            echo "No records matching your query were found.";
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    //free memory associated with result
    $result->close();

    // Close connection
    mysqli_close($conn);
  }
}
?>
