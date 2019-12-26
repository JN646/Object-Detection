<?php
require_once "classes/loader.class.php";
header('Content-Type: application/json');

$db = new DB();

$accounts = $db->query('SELECT `count_id`, `count_time`, `class_name`, `count_confidence` FROM `counter` INNER JOIN `class_types` ON `class_number` = `count_class`')->fetchAll();

$json = json_encode(['data' => $accounts], JSON_PRETTY_PRINT);

echo $json;
?>
