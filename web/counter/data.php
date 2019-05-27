<?php
// Import
include '../classes/class_objectDetection.php';

// Create new object
$foo = new ObjectDetection();
echo "<h1>" . $foo->liveObjectCounter("person",2) . "</h1>";
?>
