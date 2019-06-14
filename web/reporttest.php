<?php
require_once 'classes/class_reports.php';
require_once 'functions/reports.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Report Test</title>
    <style media="screen">
      body {
        font-family: sans-serif;
      }
    </style>
  </head>
  <body>
    <h1>Report Tester</h1>
    <fieldset>
      <legend>Report Builder</legend>
      <form class="" action="index.html" method="post">
        <!-- Table -->
        <label for="table">Table</label>
        <select class="" name="table">
          <option value="counter">counter</option>
        </select>
        <br>

        <?php
          $db = dbconnect();
          $sql = "SHOW COLUMNS FROM counter";
          $result = $db->query($sql);
          echo "n results: " . $result->num_rows . "<br>";
          $tableColumns = $result->fetch_array();
        ?>

        <!-- Columns -->
        <label for="selectColumn">Column</label>
        <select class="" name="selectColumn" id="col"></select>
        <script>
          var selectBox = document.getElementById('col');
          var tableColumns = ["foo", "bar", "baz"]; // this needs to come from the SQL result above
          tableColumns.forEach(option => selectBox.add(new Option(option)));
        </script>

        <!-- Count -->
        <input type="checkbox" name="isCount" value="">
        <label for="">Count</label>

        <br>

        <!-- Condition -->
        <label for="whatComparitor">Comparitor</label>
        <select class="" name="whatComparitor">
          <option value="">=</option>
          <option value="">></option>
          <option value=""><</option>
          <option value="">>=</option>
          <option value=""><=</option>
          <option value="">!=</option>
        </select>

        <!-- Value -->
        <label for="whatValue">Value</label>
        <input type="text" name="whatValue" value="">
      </form>
    </fieldset>
  </body>
</html>
