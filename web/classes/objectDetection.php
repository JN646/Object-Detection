<?php
class objectDetection
{
  private $id;
  private $deviceID;
  private $class;
  private $time;
  private $confidence;

  public function __construct(){}

  public function dbconnect() {
    $conn = mysqli_connect("localhost", "root", "", "objectTracker2");

    if (!$conn) {
      echo "Error: Unable to connect to MySQL." . PHP_EOL;
      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
      exit;
    }

    return $conn;
  }

  // Format Confidence
  public function formatConfidence()
  {
    return number_format($this->confidence,2) . "%";
  }

  public function formatConfidenceColours()
  {
    if (number_format($this->confidence,2) > 0.5) {
      $color = "text-danger";
    }

    if (number_format($this->confidence,2) > 0.75) {
      $color = "text-warning";
    }

    if (number_format($this->confidence,2) > 0.9) {
      $color = "text-success";
    }

    return $color;
  }


  // Select all records and make table.
  public function selectAllTable()
  {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = "SELECT * FROM counter LIMIT 25";
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table class='table table-sm'>";
                echo "<tr>";
                    echo "<th class='text-center'><input id='doSelectAll' class='doCheckbox' type='checkbox'></th>";
                    echo "<th class='text-center'>ID</th>";
                    echo "<th class='text-center'>Device ID</th>";
                    echo "<th class='text-center'>Class</th>";
                    echo "<th class='text-center'>Time</th>";
                    echo "<th class='text-center'>Conf.</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                $this->id = $row['count_id'];
                $this->deviceID = $row['count_deviceID'];
                $this->class = ucfirst($row['count_class']);
                $this->time = $row['count_time'];
                $this->confidence = $row['count_confidence'];
                echo "<tr>";
                    echo "<td class='text-center'><input class='doCheckbox' type='checkbox' value='".$this->id."'></td>";
                    echo "<td class='text-center'>" . $this->id . "</td>";
                    echo "<td class='text-center'>" . $this->deviceID . "</td>";
                    echo "<td>" . $this->class . "</td>";
                    echo "<td>" . date("h:i:s d/m/y", strtotime($this->time)) . "</td>";
                    echo "<td class='text-center ". $this->formatConfidenceColours() ."'>" . $this->formatConfidence() . "</td>";
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

    // Close connection
    mysqli_close($conn);
  }

  // Count first time.
  public function countFirstTime()
  {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT MIN(count_time) FROM counter LIMIT 1");
    $row = $sql->fetch_row();
    $count = $row[0];

    return $count;
  }

  // Device last time.
  public function countDeviceLastTime($deviceID) {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT MAX(count_time) FROM counter WHERE count_deviceID = 1 LIMIT 1");
    $row = $sql->fetch_row();
    $count = $row[0];

    return $count;
  }

  // Count last time.
  public function countLastTime()
  {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT MAX(count_time) FROM counter LIMIT 1");
    $row = $sql->fetch_row();
    $count = $row[0];

    return $count;
  }

  // Select all devices
  public function selectAllDevices() {
    // Connect to the database.
    $conn = $this->dbconnect();

    // SQL
    $sql = "SELECT DISTINCT count_deviceID
    FROM counter
    ORDER BY count_deviceID
    DESC
    LIMIT 5";

    // If results is true.
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm'>";
                echo "<tr>";
                    echo "<th class='text-center'>Name</th>";
                    echo "<th class='text-center'>ID</th>";
                    echo "<th class='text-center'>Last Seen</th>";
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)){

                // Map variables.
                $deviceID = $row['count_deviceID'];
                $name = "";

                if ($name == "") {
                  $name = "N/A";
                }

                // Generate Table Rows.
                echo "<tr>";
                    echo "<td>{$name}</td>";
                    echo "<td class='text-center'>{$deviceID}</td>";
                    echo "<td class='text-center'>". date("h:i:s d/m/y", strtotime($this->countDeviceLastTime($deviceID))) ."</td>";
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

    // Close connection
    mysqli_close($conn);
  }

  // Select all class types
  public function selectAllClassTypes() {
    // Connect to the database.
    $conn = $this->dbconnect();

    // SQL
    $sql = "SELECT count_class, COUNT(count_class) AS count
    FROM counter
    GROUP BY count_class
    ORDER BY `count`
    DESC
    LIMIT 5";

    // If results is true.
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
          // Generate the table.
            echo "<table class='table table-sm'>";
                echo "<tr>";
                    echo "<th class='text-center'>Class</th>";
                    echo "<th class='text-center'>Count</th>";
                echo "</tr>";

            // For Each.
            while($row = mysqli_fetch_array($result)){

                // Map variables.
                $this->class = $row['count_class'];
                $count = $row['count'];

                // Generate Table Rows.
                echo "<tr>";
                    echo "<td>" . ucfirst($this->class) . "</td>";
                    echo "<td class='text-center'>" . $count . "</td>";
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

    // Close connection
    mysqli_close($conn);
  }

  // Stats
  // STAT Count Data Rows
  public function countData()
  {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT COUNT(*) FROM counter");
    $row = $sql->fetch_row();
    $count = $row[0];

    return $count;
  }

  // STAT Count Classes
  public function countClasses()
  {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT COUNT(DISTINCT count_class) FROM counter");
    $row = $sql->fetch_row();
    $count = $row[0];

    return $count;
  }

  // STAT Count Device IDs
  public function countDeviceID()
  {
    // Attempt select query execution
    $conn = $this->dbconnect();
    $sql = $conn->query("SELECT COUNT(DISTINCT count_deviceID) FROM counter");
    $row = $sql->fetch_row();
    $count = $row[0];

    return $count;
  }

  // Chart Class
  public function chartClass() {
    $mysqli = $this->dbconnect();
    //query to get data from the table
    $query = sprintf("SELECT count_class, COUNT(count_class) AS count FROM counter GROUP BY count_class");

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

  // Chart Total
  public function chartTotal() {
    $mysqli = $this->dbconnect();
    //query to get data from the table
    $query = sprintf("SELECT count_time, count_id FROM counter GROUP BY count_id");

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

  // STAT Generate Class
  public function tableStats()
  {
    // Attempt select query execution
    echo "<table class='table table-sm'>";
        echo "<tr>";
            echo "<th class='text-center'>Stats</th>";
            echo "<th class='text-center'>Count</th>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>Rows</td>";
            echo "<td class='text-center'>". $this->countData() ."</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>Classes</td>";
            echo "<td class='text-center'>". $this->countClasses() ."</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>Device IDs</td>";
            echo "<td class='text-center'>". $this->countDeviceID() ."</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>First Event</td>";
            echo "<td class='text-center'><i class='far fa-clock' title='".date('h:i:s d/m/y', strtotime($this->countFirstTime()))."'></i></td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td>Last Event</td>";
            echo "<td class='text-center'><i class='far fa-clock' title='".date('h:i:s d/m/y', strtotime($this->countLastTime()))."'></i></td>";
        echo "</tr>";
    echo "</table>";
  }
}
?>
