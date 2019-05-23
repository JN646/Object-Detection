<?php
require_once '../classes/objectDetection.php';
//
// //setting header to json
header('Content-Type: application/json');

// Class Chart
$foo = new ObjectDetection();
$foo->chartClass();

// Total Chart
// $foo2 = new ObjectDetection();
// $foo2->chartTotal();
