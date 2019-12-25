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

/**
 * Application Version
 */
class ApplicationVersion {
	// Define version numbering
	const MAJOR = 0;
	const MINOR = 1;
	const PATCH = 0;

	public static function get() {
		// Prepare git information to form version number.
		$commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

		// Get date and time information.
		$commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
		$commitDate->setTimezone(new \DateTimeZone('UTC'));

		// Format all information into a version identifier.
		return sprintf('v%s.%s.%s-dev.%s (%s)', self::MAJOR, self::MINOR, self::PATCH, $commitHash, $commitDate->format('Y-m-d H:m:s'));
	}

	// Usage: echo 'MyApplication ' . ApplicationVersion::get();
}

?>
