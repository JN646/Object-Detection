<?php
# ============================================================================
# Test funciton to test an echo.
# ============================================================================
function testFunction() {
  echo "This is a test";
}

# ============================================================================
# If no date
# ============================================================================
function ifNoDate($date) {
  if ($date == "00:00:00 01/01/70" || empty($date)) {
    // Never Seen
    $date = "Never Seen";
  }

  return $date;
}

# ============================================================================
# Ping Device
# ============================================================================
function pingDomain($domain) {
    $starttime = microtime(true);
    $file      = fsockopen ($domain, 80, $errno, $errstr, 10);
    $stoptime  = microtime(true);
    $status    = 0;

    if (!$file) $status = -1;  // Site is down
    else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }
    return $status;
}

# ============================================================================
# Format number 1k, 1m, 1b
# ============================================================================
function numberFormatShort($n) {
  $n_format = 0;
  $suffix = '';
  
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
# Format Confidence Colours
# ============================================================================
function formatConfidenceColours($confidence) {
  // Low
  if (number_format($confidence,2)) {$color = "text-danger";}

  // Medium
  if (number_format($confidence,2) > 0.75) {$color = "text-warning";}

  // Good
  if (number_format($confidence,2) > 0.9) {$color = "text-success";}

  return $color;
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

# ============================================================================
# Get Notification Priority
# ============================================================================
function getPriority($notificationPriority) {
  // Get Priority
  switch ($notificationPriority) {
    case 0:
      return "<i class='text-info fas fa-info'></i>";
      break;
    case 1:
      return "<i class='text-warning fas fa-exclamation'></i>";
      break;
    case 2:
      return "<i class='text-danger fas fa-exclamation-triangle'></i>";
      break;
    default:
      return "Other";
      break;
  }
}

# ============================================================================
# Get Notification Category
# ============================================================================
function getCategory($notificationCategory) {
  // Get Priority
  switch ($notificationCategory) {
    case 0:
      return "Info";
      break;
    case 1:
      return "Weather";
      break;
    case 2:
      return "Incident";
      break;
    default:
      return "Other";
      break;
  }
}
?>
