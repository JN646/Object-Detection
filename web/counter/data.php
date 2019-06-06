<?php
// Import
require_once '../classes/class_objectDetection.php';

// Create new object
$foo = new ObjectDetection();
echo "<h1>" . $foo->liveObjectCounter("ALL",2) . "</h1>";
?>
