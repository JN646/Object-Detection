<?php
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

function csvOutputFormat() {

}

if (isset($_POST['csvOutput'])) {
  $db = dbconnect();

  //get records from database
  $query = $db->query("SELECT * FROM `counter` INNER JOIN `class_types` ON counter.count_class = class_types.class_number INNER JOIN `devices` ON counter.count_deviceID = devices.device_id ORDER BY `count_id` DESC");

  // If there are returned rows.
  if($query->num_rows > 0){
      $delimiter = ",";
      $filename = "data_" . date('Y-m-d') . ".csv";

      //create a file pointer
      $f = fopen('php://memory', 'w');

      //set column headers
      $fields = array('ID', 'Device', 'Class', 'Confidence', 'Lat', 'Long');
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      while($row = $query->fetch_assoc()){
          $lineData = array($row['count_id'], $row['device_name'], $row['class_name'], $row['count_confidence'], $row['count_lat'], $row['count_long']);
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

if (isset($_POST['csvTodayOutput'])) {
  $db = dbconnect();
  $today = date("Y-m-d");

  //get records from database
  $query = $db->query("SELECT * FROM `counter` INNER JOIN `class_types` ON counter.count_class = class_types.class_number INNER JOIN `devices` ON counter.count_deviceID = devices.device_id ORDER BY `count_id` DESC");

  // If there are returned rows.
  if($query->num_rows > 0){
      $delimiter = ",";
      $filename = "today_data_" . date('Y-m-d') . ".csv";

      //create a file pointer
      $f = fopen('php://memory', 'w');

      //set column headers
      $fields = array('ID', 'Device', 'Class', 'Confidence', 'Lat', 'Long');
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      while($row = $query->fetch_assoc()){
          $lineData = array($row['count_id'], $row['device_name'], $row['class_name'], $row['count_confidence'], $row['count_lat'], $row['count_long']);
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
?>
