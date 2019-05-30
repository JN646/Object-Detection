<?php
/**
 * Object Detection
 */
require_once 'classes/common.php';

class objectDetection {
  public $id;
  public $deviceID;
  public $class;
  public $time;
  public $confidence;
  public $long;
  public $lat;

  # ============================================================================
  # Constructor
  # ============================================================================
  public function __construct(){}

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
  # Format Confidence Colours
  # ============================================================================
  public function formatConfidenceColours() {
    // Low
    if (number_format($this->confidence,2) > 0.5) {$color = "text-danger";}

    // Medium
    if (number_format($this->confidence,2) > 0.75) {$color = "text-warning";}

    // Good
    if (number_format($this->confidence,2) > 0.9) {$color = "text-success";}

    return $color;
  }

  # ============================================================================
  # Detected Objects Table
  # ============================================================================
  public function selectAllTable() {
    // Attempt select query execution
    // SELECT * FROM `counter` INNER JOIN class_types ON counter.count_class = class_types.class_number
    $conn = $this->dbconnect();
    if($result = mysqli_query($conn, "SELECT * FROM `counter` INNER JOIN `class_types` ON counter.count_class = class_types.class_number INNER JOIN `devices` ON counter.count_deviceID = devices.device_id ORDER BY `count_id` DESC")) {
        if(mysqli_num_rows($result) > 0){
            echo "<table class='table table-sm'>";
                echo "<tr>";
                    echo "<th class='text-center'><input id='doSelectAll' class='doCheckbox' type='checkbox'></th>";
                    echo "<th class='text-center'>ID</th>";
                    echo "<th class='text-center'>Device Name</th>";
                    echo "<th class='text-center'>Class</th>";
                    echo "<th class='text-center'>Time</th>";
                    echo "<th class='text-center'>Conf.</th>";
                    echo "<th class='text-center'>GPS</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                // Assign fetched variables to class
                $this->id = $row['count_id'];
                $this->deviceID = $row['device_name'];
                $this->classIcon = $row['class_icon'];
                $this->class = ucfirst($row['class_name']);
                $this->time = $row['count_time'];
                $this->confidence = $row['count_confidence'];
                $this->lat = $row['count_lat'];
                $this->long = $row['count_long'];

                $latLong = $this->lat . " " . $this->long;

                // Draw table
                echo "<tr>";
                    echo "<td class='text-center'><input class='doCheckbox' type='checkbox' value='{$this->id}'></td>";
                    echo "<td class='text-center'>{$this->id}</td>";
                    echo "<td class='text-center'>{$this->deviceID}</td>";
                    echo "<td>{$this->classIcon} {$this->class}</td>";
                    echo "<td>" . date("H:i:s d/m/y", strtotime($this->time)) . "</td>";
                    echo "<td class='text-center {$this->formatConfidenceColours()}'>".formatConfidence($this->confidence)."</td>";
                    if (empty($this->lat) || empty($this->long)) {
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
  # Count First Event Time
  # ============================================================================
  public function countFirstLastTime($input) {
    // Attempt select query execution
    $conn = $this->dbconnect();
    if ($input == "MIN") {
      $sql = $conn->query("SELECT MIN(`count_time`) FROM `counter` LIMIT 1");
    }

    if ($input == "MAX") {
      $sql = $conn->query("SELECT MAX(`count_time`) FROM `counter` LIMIT 1");
    }
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
  # Select All Devices
  # ============================================================================
  public function selectAllDevices() {
    // Connect to the database.
    $conn = $this->dbconnect();

    // SQL
    $sql = "SELECT DISTINCT (`count_deviceID`), `count_time`, `count_lat`, `count_long`, `device_name`, `device_location`
    FROM `counter`
    INNER JOIN `devices` ON counter.count_deviceID = devices.device_id
    ORDER BY `count_deviceID`
    DESC
    LIMIT 5";

    // If results is true.
    if($result = mysqli_query($conn, $sql)) {
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm'>";
                echo "<tr>";
                    echo "<th class='text-center'>Name</th>";
                    echo "<th class='text-center'>Location</th>";
                    echo "<th class='text-center'>Last Seen</th>";
                    echo "<th class='text-center'>GPS</th>";
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)) {

                // Map variables.
                $this->deviceID = $row['count_deviceID'];
                $this->deviceName = $row['device_name'];
                $this->deviceLocation = $row['device_location'];
                $this->lat = $row['count_lat'];
                $this->long = $row['count_long'];

                $latLong = $this->lat . " " . $this->long;

                // If blank
                if ($name == "") {
                  $name = "N/A";
                }

                // Generate Table Rows.
                echo "<tr>";
                    echo "<td>{$this->deviceName}</td>";
                    echo "<td class='text-center'>{$this->deviceLocation}</td>";
                    echo "<td class='text-center'>". date("H:i:s d/m/y", strtotime($this->countDeviceLastTime($this->deviceID))) ."</td>";
                    if (empty($this->lat) || empty($this->long)) {
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
  # Select All Class Types
  # ============================================================================
  public function selectAllClassTypes() {
    // Connect to the database.
    $conn = $this->dbconnect();

    // SQL
    $sql = "SELECT class_types.class_name, class_types.class_icon, COUNT(class_types.class_name) AS `count` FROM `counter`
      JOIN class_types ON counter.count_class = class_types.class_number
      GROUP BY class_types.class_name, class_types.class_icon
      ORDER BY `count` DESC
      LIMIT 5";

    // If results is true.
    if($result = mysqli_query($conn, $sql)) {
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm'>";
                echo "<tr>";
                    echo "<th class='text-center'>Icon</th>";
                    echo "<th class='text-center'>Class</th>";
                    echo "<th class='text-center'>Count</th>";
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)){

                // Map variables.
                $this->class = $row['class_name'];
                $count = $row['count'];
                $classIcon = $row['class_icon'];

                // Generate Table Rows.
                echo "<tr>";
                    echo "<td  class='text-center'>{$classIcon}</td>";
                    echo "<td>" . ucfirst($this->class) . "</td>";
                    echo "<td class='text-center'>" . numberFormatShort($count) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    //free memory associated with result
    $result->close();

    // Close connection
    mysqli_close($conn);
  }

  # ============================================================================
  # STATS
  # ============================================================================
  # ============================================================================
  # Stats: Table
  # ============================================================================
  public function tableStats() {
    // Attempt select query execution
    echo "<table class='table table-sm'>";
        echo "<tr>";
            echo "<th class='text-center'>Stats</th>";
            echo "<th class='text-center'>Count</th>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>Rows</td>";
            echo "<td class='text-center'>". numberFormatShort($this->countThings('All')) ."</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>Classes</td>";
            echo "<td class='text-center'>". numberFormatShort($this->countThings('count_class')) ."</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>Device IDs</td>";
            echo "<td class='text-center'>". numberFormatShort($this->countThings('count_deviceID')) ."</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>First Event</td>";
            echo "<td class='text-center'><i class='far fa-clock' title='".date('h:i:s d/m/y', strtotime($this->countFirstLastTime("MIN")))."'></i></td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>Last Event</td>";
            echo "<td class='text-center'><i class='far fa-clock' title='".date('h:i:s d/m/y', strtotime($this->countFirstLastTime("MAX")))."'></i></td>";
        echo "</tr>";
    echo "</table>";
  }

  # ============================================================================
  # Stats: All Template
  # ============================================================================
  public function countThings($input) {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Adapt query based on input variable.
    if ($input == "All") {
      $sql = $conn->query("SELECT COUNT(DISTINCT `count_id`) FROM `counter`");
    } else {
      $sql = $conn->query("SELECT COUNT(DISTINCT $input) FROM `counter`");
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
  # CHARTS
  # ============================================================================
  # ============================================================================
  # Class Chart
  # ============================================================================
  public function chartClass() {
    $mysqli = $this->dbconnect();
    //query to get data from the table
    $query = sprintf("SELECT `count_class`, COUNT(`count_class`) AS `count` FROM `counter` GROUP BY `count_class`");

    //execute query
    $result = $mysqli->query($query);

    //loop through the returned data
    $data = array();
    foreach ($result as $row) {
    	$data[] = $row;
    }

    //free memory associated with result
    $result->close();

    //close connection
    $mysqli->close();

    //now print the data
    print json_encode($data);
  }

  # ============================================================================
  # Total Chart
  # ============================================================================
  public function chartTotal() {
    $mysqli = $this->dbconnect();
    //query to get data from the table
    $query = sprintf("SELECT `count_time`, `count_id` FROM `counter` GROUP BY `count_id`");

    //execute query
    $result = $mysqli->query($query);

    //loop through the returned data
    $total = array();
    foreach ($result as $row) {
      $total[] = $row;
    }

    //free memory associated with result
    $result->close();

    //close connection
    $mysqli->close();

    //now print the data
    print json_encode($total);
  }

  public function liveObjectCounter($class, $deviceID) {
    $conn = $this->dbconnect();

    // Get the latest count.
    if ($class == "ALL" && $deviceID == "ALL") {
      $result = $conn->query("SELECT COUNT(*) as count FROM counter WHERE count_time IN (SELECT MAX(count_time) FROM counter)");
    } else {
      $result = $conn->query("SELECT COUNT(*) as count FROM counter WHERE count_time IN (SELECT MAX(count_time) FROM counter) AND count_class = '$class' AND count_deviceID = '$deviceID'");
    }

    // If there are results.
    if ($result->num_rows > 0) {
    	while ($row = $result->fetch_assoc()) {
    		$count = $row['count'];

    		// Output count.
    		return $count;
    	}
    } else {
    	// If there are no results.
    	return "N/A";
    }
  }
}
?>
