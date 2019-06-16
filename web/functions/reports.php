<?php
// Error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require_once 'classes/class_notification.php';
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
# Output All
# ============================================================================
if (isset($_POST['csvOutput'])) {
  csvOutput();
}

# ============================================================================
# Output Today
# ============================================================================
if (isset($_POST['csvTodayOutput'])) {
  $db = dbconnect();
  $today = date("Y-m-d");
  $todayStart = "{$today} 00:00:00";
  $todayEnd = "{$today} 23:59:59";

  //get records from database
  $sql = "SELECT * FROM `counter`
  INNER JOIN `class_types` ON counter.count_class = class_types.class_number
  INNER JOIN `devices` ON counter.count_deviceID = devices.device_id
  WHERE counter.count_time > '$todayStart' AND counter.count_time < '$todayEnd'
  ORDER BY `count_id`
  DESC";

  // Run SQL
  $query = $db->query($sql);

  // If there are returned rows.
  if($query->num_rows > 0){
      $delimiter = ",";
      $filename = "today_data_" . date('Y-m-d') . ".csv";

      //create a file pointer
      $f = fopen('php://memory', 'w');

      //set column headers
      $fields = array(
        'ID',
        'Device',
        'Class',
        'Confidence',
        'Lat',
        'Long'
      );
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      while($row = $query->fetch_assoc()){
          $lineData = array(
            $row['count_time'],
            $row['count_id'],
            $row['device_name'],
            $row['class_name'],
            $row['count_confidence'],
            $row['count_lat'],
            $row['count_long']
          );
          fputcsv($f, $lineData, $delimiter);
      }

      //move back to beginning of file
      fseek($f, 0);

      //set headers to download file rather than displayed
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '";');

      //output all remaining data on a file pointer
      fpassthru($f);
  } else {
    echo "No Data Today";
  }
  exit;
}

# ============================================================================
# Date Select Go
# ============================================================================
if (isset($_POST['csvDateSelectGo'])) {
  $db = dbconnect();

  // Get start and end date.
  $todayStart = $_POST["dateSelectStart"];
  $todayEnd =  $_POST["dateSelectEnd"];

  //get records from database
  $sql = "SELECT * FROM `counter`
  INNER JOIN `class_types` ON counter.count_class = class_types.class_number
  INNER JOIN `devices` ON counter.count_deviceID = devices.device_id
  WHERE counter.count_time > '$todayStart' AND counter.count_time < '$todayEnd'
  ORDER BY `count_id`
  DESC";

  // Run SQL
  $query = $db->query($sql);

  // If there are returned rows.
  if($query->num_rows > 0){
      $delimiter = ",";
      $filename = "today_data_" . date('Y-m-d') . ".csv";

      //create a file pointer
      $f = fopen('php://memory', 'w');

      //set column headers
      $fields = array(
        'ID',
        'Device', '
        Class',
        'Confidence',
        'Left',
        'Top',
        'Right',
        'Bottom',
        'Lat',
        'Long');
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      while($row = $query->fetch_assoc()){
          $lineData = array(
            $row['count_time'],
            $row['count_id'],
            $row['device_name'],
            $row['class_name'],
            $row['count_left'],
            $row['count_top'],
            $row['count_right'],
            $row['count_bottom'],
            $row['count_confidence'],
            $row['count_lat'],
            $row['count_long']);
          fputcsv($f, $lineData, $delimiter);
      }

      //move back to beginning of file
      fseek($f, 0);

      //set headers to download file rather than displayed
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '";');

      //output all remaining data on a file pointer
      fpassthru($f);
  } else {
    echo "<div class='alert alert-danger'>No Data Today</div>";
  }
  exit;
}

# ============================================================================
# Output Notifications
# ============================================================================
if (isset($_POST['csvNotificationOutput'])) {
  csvNotificationOutput();
}

# ============================================================================
# Output Devices
# ============================================================================
if (isset($_POST['csvDeviceOutput'])) {
  csvDeviceOutput();
}

# ============================================================================
# Output Classes
# ============================================================================
if (isset($_POST['csvClassesOutput'])) {
  csvClassesOutput();
}

# ============================================================================
# Functions
# ============================================================================
# ============================================================================
# CSV Notifications
# ============================================================================
function csvNotificationOutput() {
  $db = dbconnect();

  //get records from database
  $query = $db->query("SELECT * FROM `notification`");

  // If there are returned rows.
  if($query->num_rows > 0){
      $delimiter = ",";
      $filename = "notification_data_" . date('Y-m-d') . ".csv";

      //create a file pointer
      $f = fopen('php://memory', 'w');

      //set column headers
      $fields = array('ID', 'Priority', 'Device', 'Category', 'Time', 'Message');
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      while($row = $query->fetch_assoc()){
          $lineData = array($row['notification_id'], $row['notification_priority'], $row['notification_deviceID'], $row['notification_category'], $row['notification_datetime'], $row['notification_message']);
          fputcsv($f, $lineData, $delimiter);
      }

      //move back to beginning of file
      fseek($f, 0);

      //set headers to download file rather than displayed
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '";');

      //output all remaining data on a file pointer
      fpassthru($f);
  } else {
    echo "<div class='alert alert-danger'>No Notifications</div>";
  }
  exit;
}

# ============================================================================
# CSV Output All
# ============================================================================
function csvOutput() {
  $db = dbconnect();

  //get records from database
  $sql = "SELECT * FROM `counter`
  INNER JOIN `class_types` ON counter.count_class = class_types.class_number
  INNER JOIN `devices` ON counter.count_deviceID = devices.device_id
  ORDER BY `count_id`
  DESC";

  $query = $db->query($sql);

  // If there are returned rows.
  if($query->num_rows > 0){
      $delimiter = ",";
      $filename = "data_" . date('Y-m-d') . ".csv";

      //create a file pointer
      $f = fopen('php://memory', 'w');

      //set column headers
      $fields = array(
        'ID',
        'Device', '
        Class',
        'Confidence',
        'Left',
        'Top',
        'Right',
        'Bottom',
        'Lat',
        'Long');
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      while($row = $query->fetch_assoc()){
          $lineData = array(
            $row['count_time'],
            $row['count_id'],
            $row['device_name'],
            $row['class_name'],
            $row['count_left'],
            $row['count_top'],
            $row['count_right'],
            $row['count_bottom'],
            $row['count_confidence'],
            $row['count_lat'],
            $row['count_long']
          );

          fputcsv($f, $lineData, $delimiter);
      }
      //move back to beginning of file
      fseek($f, 0);

      //set headers to download file rather than displayed
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '";');

      //output all remaining data on a file pointer
      fpassthru($f);
  }
  exit;
}

# ============================================================================
# CSV Devices
# ============================================================================
function csvDeviceOutput() {
  $db = dbconnect();

  //get records from database
  $query = $db->query("SELECT * FROM `devices`");

  // If there are returned rows.
  if($query->num_rows > 0){
      $delimiter = ",";
      $filename = "devices_data_" . date('Y-m-d') . ".csv";

      //create a file pointer
      $f = fopen('php://memory', 'w');

      //set column headers
      $fields = array('ID', 'Name', 'Location', 'IP', 'Last Ping', 'Version', 'Confidence Threshold');
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      while($row = $query->fetch_assoc()){
          $lineData = array(
            $row['device_id'],
            $row['device_name'],
            $row['device_location'],
            $row['device_ip'],
            $row['device_lastPing'],
            $row['device_clientVersion'],
            $row['device_confidenceThreshold']
          );

          fputcsv($f, $lineData, $delimiter);
      }

      //move back to beginning of file
      fseek($f, 0);

      //set headers to download file rather than displayed
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '";');

      //output all remaining data on a file pointer
      fpassthru($f);
  } else {
    echo "<div class='alert alert-danger'>No Devices</div>";
  }

  if (!$query) {
    die("Error:" . mysqli_error($db));
  }

  exit;
}

# ============================================================================
# CSV Devices
# ============================================================================
function csvClassesOutput() {
  $db = dbconnect();

  //get records from database
  $query = $db->query("SELECT * FROM `class_types`");

  // If there are returned rows.
  if($query->num_rows > 0){
      $delimiter = ",";
      $filename = "classes_data_" . date('Y-m-d') . ".csv";

      //create a file pointer
      $f = fopen('php://memory', 'w');

      //set column headers
      $fields = array('ID', 'Number', 'Class Name');
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      while($row = $query->fetch_assoc()){
          $lineData = array(
            $row['class_id'],
            $row['class_number'],
            $row['class_name'],
          );

          fputcsv($f, $lineData, $delimiter);
      }

      //move back to beginning of file
      fseek($f, 0);

      //set headers to download file rather than displayed
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '";');

      //output all remaining data on a file pointer
      fpassthru($f);
  } else {
    echo "<div class='alert alert-danger'>No Classes</div>";
  }

  if (!$query) {
    die("Error:" . mysqli_error($db));
  }

  exit;
}

?>
