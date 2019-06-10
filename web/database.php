<?php
include 'classes/class_database.php';

$foo = new Database();

// Error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Database Test</title>
    <style media="screen">
      * {
        font-family: sans-serif;
      }
    </style>
  </head>
  <body>
    <h1>Database Test</h1>
    <p>Number of Tables: <span><?php echo $foo->countTables(); ?></span></p>
    <?php $foo->getTableList(); ?>
  </body>
</html>
