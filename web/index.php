<?php
// Classes
require_once 'classes/objectDetection.php';
require_once 'partials/_header.php';
$foo = new ObjectDetection();
?>
<body onload="startTime()">
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="index.php">Object Detection</a>
  <span id='clock'>Clock</span>
</nav>
<div class='col-md-12 fluid-container'>
  <h1>Dashboard</h1>
  <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group mr-2" role="group" aria-label="First group">
      <button onclick='toggleDetectedObj()' type="button" class="btn btn-outline-dark">Detected Obj</button>
      <button onclick='toggleClasses()' type="button" class="btn btn-outline-dark">Classes</button>
      <button onclick='toggleDevices()' type="button" class="btn btn-outline-dark">Devices</button>
      <button onclick='toggleStats()' type="button" class="btn btn-outline-dark">Stats</button>
    </div>
  <div class="btn-group mr-2" role="group" aria-label="Second group">
      <button onclick='toggleClassesChart()' type="button" class="btn btn-outline-dark">Classes Chart</button>
      <button onclick='toggleTotalChart()' type="button" class="btn btn-outline-dark">Total Chart</button>
    </div>
  </div>
  <!-- Detected Object Table -->
  <fieldset id='detectedObjectAllTable'>
    <legend>Detected Objects <span>(<?php echo $foo->countData() ?>)</span></legend>
    <div class="">
      <?php
      $foo->selectAllTable();
      ?>
    </div>
  </fieldset>

  <!-- Classes Found -->
  <fieldset id='classesFoundTable'>
    <legend>Classes Found <span>(<?php echo $foo->countClasses() ?>)</span></legend>
    <div class="">
      <?php
        $foo->selectAllClassTypes();
      ?>
    </div>
  </fieldset>

  <!-- Devices Found -->
  <fieldset id='devicesFoundTable'>
    <legend>Devices Found <span>(<?php echo $foo->countDeviceID() ?>)</span></legend>
    <div class="">
      <?php
        $foo->selectAllDevices();
      ?>
    </div>
  </fieldset>

  <!-- Stats -->
  <fieldset id='statsTable'>
    <legend>Stats</legend>
    <div class="">
      <?php
        $foo->tableStats();
      ?>
    </div>
  </fieldset>

  <!-- Class Chart -->
  <fieldset id='classChartFieldset'>
    <legend>Class Chart</legend>
    <div class="">
      <div id="chart-container">
        <canvas width="400px" height="400px" id="mycanvas-class"></canvas>
      </div>
    </div>
  </fieldset>

  <!-- Total Chart -->
  <fieldset id='totalChartFieldset'>
    <legend>Total Chart</legend>
    <div class="">
      <div id="chart-container-total">
        <canvas width="400px" height="400px" id="mycanvas-total"></canvas>
      </div>
    </div>
  </fieldset>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<?php require_once 'partials/_footer.php'; ?>
