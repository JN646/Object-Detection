<?php
require_once 'classes/class_reports.php';
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

        <!-- Columns -->
        <label for="whatColumns">Columns</label>
        <select class="" name="whatColumns">
          <option value="">Test</option>
        </select>

        <!-- Count -->
        <label for="">Is Count</label>
        <input type="checkbox" name="isCount" value="">

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
