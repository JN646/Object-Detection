<?php
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
  $mission = array('1 - People', '2 - Cars');

  for ($i=0; $i < count($mission); $i++) {
    return "<option>{$mission[$i]}</option>";
  }
}
?>
