<?php
/**
 * Application Class
 */
class Application {
  # ============================================================================
  # Initial
  # ============================================================================
  const APP_NAME = "Object Detection Application";
  const APP_VERSION = "1.0";
  const APP_AUTHOR = "Josh Ginn";

  # ============================================================================
  # Constructor
  # ============================================================================
  function __construct() {}

  # ============================================================================
  # Get App Details
  # ============================================================================
  function getAppDetails() {
    echo "<span>".self::APP_NAME."</span></br>";
    echo "<span>".self::APP_VERSION."</span></br>";
    echo "<span>".self::APP_AUTHOR."</span></br>";
  }
}

?>
