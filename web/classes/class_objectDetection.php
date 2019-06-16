<?php
/**
 * Object Detection
 */
require_once 'common.php';

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
  # Database Error
  # ============================================================================
  public function dbError($sql,$conn) {
    if (!$sql) {
      $error = "<div class='text-center alert alert-danger'>ERROR: Could not able to execute $sql. " . mysqli_error($conn) . "</div>";
    }

    return $error;
  }

  # ============================================================================
  # Detected Objects Table
  # ============================================================================
  public function selectAllTable() {
    // Attempt select query execution
    $conn = $this->dbconnect();
    if($result = mysqli_query($conn, "SELECT * FROM `counter` INNER JOIN `class_types` ON counter.count_class = class_types.class_number INNER JOIN `devices` ON counter.count_deviceID = devices.device_id ORDER BY `count_id` DESC")) {
        $headers = array("#","Device Name","Class","Time","Conf.","Loc.","GPS");
        if(mysqli_num_rows($result) > 0){
            echo "<table id='detectedObjectAllTable' class='table table-sm table-bordered'>";
                echo "<tr>";
                    echo "<th class='text-center'><input id='doSelectAll' class='doCheckbox' type='checkbox'></th>";
                    for ($i=0; $i < count($headers); $i++) {
                      echo "<th class='text-center'>{$headers[$i]}</th>";
                    }
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                // Assign fetched variables to class
                $id = $row['count_id'];
                $deviceID = $row['device_name'];
                $classIcon = $row['class_icon'];
                $class = ucfirst($row['class_name']);
                $time = $row['count_time'];
                $this->confidence = $row['count_confidence'];
                $lat = $row['count_lat'];
                $long = $row['count_long'];
                $loc = [$row['count_left'],$row['count_top'],$row['count_right'],$row['count_bottom']];
                $latLong = $lat . " " . $long;

                // If icon is empty.
                if ($classIcon == '') {
                  $classIcon = "<i class='fas fa-question'></i>";
                }

                // Draw table
                echo "<tr>";
                    echo "<td class='text-center'><input class='doCheckbox' type='checkbox' value='{$id}'></td>";
                    echo "<td class='text-center'>{$id}</td>";
                    echo "<td class='text-center'>{$deviceID}</td>";
                    echo "<td>{$classIcon} {$class}</td>";
                    echo "<td>" . date("H:i:s d/m/y", strtotime($time)) . "</td>";
                    echo "<td class='text-center ".formatConfidenceColours($this->confidence)."'>".formatConfidence($this->confidence)."</td>";
                    echo "<td class='text-center'><i class='fas fa-vector-square' data-toggle='tooltip' title='← {$loc[0]} ↑ {$loc[1]} → {$loc[2]} ↓ {$loc[3]}'></i></td>";
                    if (empty($lat) || empty($long)) {
                      echo "<td class='text-center'></td>";
                    } else {
                      echo "<td class='text-center'><i class='fas fa-globe-europe' data-toggle='tooltip' title='{$latLong}'></i></td>";
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
  # Detected Objects Table
  # ============================================================================
  public function selectAllTableExtended() {
    // Attempt select query execution
    $conn = $this->dbconnect();
    if($result = mysqli_query($conn, "SELECT * FROM `counter` INNER JOIN `class_types` ON counter.count_class = class_types.class_number INNER JOIN `devices` ON counter.count_deviceID = devices.device_id ORDER BY `count_id` DESC")) {
        $headers = array("#","Device Name","Class","Time","Conf.","Loc.","GPS","Delete");
        if(mysqli_num_rows($result) > 0){
            echo "<table id='detectedObjectAllTable' class='table table-sm table-bordered'>";
                echo "<tr>";
                    echo "<th class='text-center'><input id='doSelectAll' class='doCheckbox' type='checkbox'></th>";
                    for ($i=0; $i < count($headers); $i++) {
                      echo "<th class='text-center'>{$headers[$i]}</th>";
                    }
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                // Assign fetched variables to class
                $id = $row['count_id'];
                $deviceID = $row['device_name'];
                $classIcon = $row['class_icon'];
                $class = ucfirst($row['class_name']);
                $time = $row['count_time'];
                $this->confidence = $row['count_confidence'];
                $lat = $row['count_lat'];
                $long = $row['count_long'];
                $loc = [$row['count_left'],$row['count_top'],$row['count_right'],$row['count_bottom']];
                $latLong = $lat . " " . $long;

                // If icon is empty.
                if ($classIcon == '') {
                  $classIcon = "<i class='fas fa-question'></i>";
                }

                // Draw table
                echo "<tr>";
                    echo "<td class='text-center'><input class='doCheckbox' type='checkbox' value='{$id}'></td>";
                    echo "<td class='text-center'>{$id}</td>";
                    echo "<td class='text-center'>{$deviceID}</td>";
                    echo "<td>{$classIcon} {$class}</td>";
                    echo "<td>" . date("H:i:s d/m/y", strtotime($time)) . "</td>";
                    echo "<td class='text-center ".formatConfidenceColours($this->confidence)."'>".formatConfidence($this->confidence)."</td>";
                    echo "<td class='text-center'><i class='fas fa-vector-square' title='← {$loc[0]} ↑ {$loc[1]} → {$loc[2]} ↓ {$loc[3]}'></i></td>";
                    if (empty($lat) || empty($long)) {
                      echo "<td class='text-center'></td>";
                    } else {
                      echo "<td class='text-center'><i class='fas fa-globe-europe' title='{$latLong}'></i></td>";
                    }
                    echo "<td onclick='return confirm('Are you sure you want to delete all notifications?');' class='text-center'><a href='functions/func_detectedObjects.php?delete_id={$id}'><i class='fas fa-trash text-danger'></i></a></td>";
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
  # Count First Event Time
  # ============================================================================
  public function countFirstLastTime($input) {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Get either first or last time.
    switch ($input) {
      // First
      case 'MIN':
        $sql = $conn->query("SELECT MIN(`count_time`) FROM `counter` LIMIT 1");
        break;

      // Last
      case 'MAX':
        $sql = $conn->query("SELECT MAX(`count_time`) FROM `counter` LIMIT 1");
        break;
    }

    // Get Rows
    $row = $sql->fetch_row();
    $count = $row[0];

    // Close connection
    mysqli_close($conn);

    return $count;
  }

  # ============================================================================
  # Select All Class Types
  # ============================================================================
  public function selectAllClassTypes($limit) {
    // Connect to the database.
    $conn = $this->dbconnect();

    // SQL
    if ($limit != 0) {
      $limitString = "LIMIT " . $limit;
    } else {
      $limitString = "";
    }

    $sql = "SELECT class_types.class_name, class_types.class_icon, COUNT(class_types.class_name) AS `count` FROM `counter`
      JOIN class_types ON counter.count_class = class_types.class_number
      GROUP BY class_types.class_name, class_types.class_icon
      ORDER BY `count` DESC $limitString";

    // If results is true.
    if($result = mysqli_query($conn, $sql)) {
      $headers = array("Icon","Class","Count");
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm table-bordered'>";
                echo "<tr>";
                  for ($i=0; $i < count($headers); $i++) {
                    echo "<th class='text-center'>{$headers[$i]}</th>";
                  }
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)){

                // Map variables.
                $this->class = $row['class_name'];
                $count = $row['count'];
                $classIcon = $row['class_icon'];

                // If icon is empty.
                if ($classIcon == '') {
                  $classIcon = "<i class='fas fa-question'></i>";
                }

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
        echo $this->dbError($sql,$conn);
    }

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
    echo "<table class='table table-sm table-bordered'>";
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
            echo "<td>First/Last Event</td>";
            echo "<td class='text-center'><i class='far fa-clock' title='".date('h:i:s d/m/y', strtotime($this->countFirstLastTime("MIN")))."'></i> <i class='far fa-clock' title='".date('h:i:s d/m/y', strtotime($this->countFirstLastTime("MAX")))."'></i></td>";
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
  # Live Counter
  # ============================================================================
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

  # ============================================================================
  # Delete All Things
  # ============================================================================
  public function deleteAllDetectedObjects() {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Delete Everything
    $sql = $conn->query("TRUCATE TABLE `counter`");

    if (!$sql) {
      die("Error:" . mysqli_error($conn));
    }

    // Get rows
    $row = $sql->fetch_row();

    // Close connection
    mysqli_close($conn);
  }

  # ============================================================================
  # Delete 1 Thing
  # ============================================================================
  public function deleteDetectedObject($record) {
    // Attempt select query execution
    $conn = $this->dbconnect();

    // Delete Everything
    $sql = $conn->query("DELETE FROM `counter` WHERE `count_id` = '$record'");

    if (!$sql) {
      die("Error:" . mysqli_error($conn));
    }

    // Close connection
    mysqli_close($conn);
  }
}
?>
