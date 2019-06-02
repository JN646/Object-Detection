<?php
# ============================================================================
# Test funciton to test an echo.
# ============================================================================
function testFunction() {
  echo "This is a test";
}

# ============================================================================
# Format number 1k, 1m, 1b
# ============================================================================
function numberFormatShort($n) {
  if ($n > 0 && $n < 1000) {
    // 1 - 999
    $n_format = floor($n);
    $suffix = '';
  } else if ($n >= 1000 && $n < 1000000) {
    // 1k-999k
    $n_format = floor($n / 1000);
    $suffix = 'K+';
  } else if ($n >= 1000000 && $n < 1000000000) {
    // 1m-999m
    $n_format = floor($n / 1000000);
    $suffix = 'M+';
  } else if ($n >= 1000000000 && $n < 1000000000000) {
    // 1b-999b
    $n_format = floor($n / 1000000000);
    $suffix = 'B+';
  } else if ($n >= 1000000000000) {
    // 1t+
    $n_format = floor($n / 1000000000000);
    $suffix = 'T+';
  }

  return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
}

# ============================================================================
# Format Confidence Value
# ============================================================================
function formatConfidence($input) {
  return number_format($input,2) . "%";
}

# ============================================================================
# List Missions
# ============================================================================
function listMissions() {
  $conn = mysqli_connect("localhost", "root", "", "objectTracker2");
  // Connect to the database.
  $result = mysqli_query($conn, "SELECT * FROM mission");

  if (!$result) {
    echo("Error description: " . mysqli_error($con));
  }

  // If results is true.
  if($result) {
    if(mysqli_num_rows($result) > 0) {
      $select = '<select name="select">';
      // For Each.
      while($row = mysqli_fetch_array($result)) {
        // Variables
        $missionID = $row['mission_id'];
        $missionName = $row['mission_name'];

        // Create option values.
        $select .= "<option value='{$missionID}'>{$missionName}</option>";
      }
      mysqli_free_result($result);
    } else {
      echo "No Values";
    }
  } else {
    echo "Error.";
  }

  $select .= '</select>';

  return $select;

  //free memory associated with result
  $result->close();

  // Close connection
  mysqli_close($conn);
}
?>
