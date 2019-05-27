<?php
# ==============================================================================
# Imports
# ==============================================================================
require_once 'classes/class_objectDetection.php';
require_once 'classes/class_application.php';
require_once 'partials/_header.php';

// Create new object.
$foo = new ObjectDetection();
?>
<!-- Start Clock -->
<body onload="startTime()">

<!-- Nav Bar -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="index.php">Object Detection</a>
  <span id='clock'>Clock</span>
</nav>

<!-- Main container -->
<div class='col-md-12 fluid-container'>
  <h1 class='display-4'>Dashboard</h1>

  <!-- Button Toolbar -->
  <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group mr-2" role="group" aria-label="First group">
      <button onclick='toggleDetectedObj()' type="button" class="btn btn-outline-dark">Detected Obj</button>
      <button onclick='toggleClasses()' type="button" class="btn btn-outline-dark">Classes</button>
      <button onclick='toggleDevices()' type="button" class="btn btn-outline-dark">Devices</button>
      <button onclick='toggleStats()' type="button" class="btn btn-outline-dark">Stats</button>
      <button onclick='toggleLiveCount()' type="button" class="btn btn-outline-dark">Live Count</button>
    </div>
    <!-- <div class="btn-group mr-2" role="group" aria-label="Second group">
        <button onclick='toggleClassesChart()' type="button" class="btn btn-outline-dark">Classes Chart</button>
        <button onclick='toggleTotalChart()' type="button" class="btn btn-outline-dark">Total Chart</button>
    </div> -->
    <div class="btn-group mr-2" role="group" aria-label="Third group">
        <button onclick='toggleDataFunctions()' type="button" class="btn btn-outline-dark">Data Func.</button>
    </div>
  </div>

  <!-- Detected Object Table -->
  <fieldset id='detectedObjectAllTable'>
    <legend>Detected Objects
      <span>(<?php echo $foo->numberFormatShort($foo->countThings("All")) ?>)</span>
    </legend>
    <div class="">
      <?php $foo->selectAllTable(); ?>
    </div>
  </fieldset>

  <!-- Classes Found -->
  <fieldset id='classesFoundTable'>
    <legend>Classes Found
      <span>(<?php echo $foo->numberFormatShort($foo->countThings('count_class')) ?>)</span>
    </legend>
    <div class="">
      <?php $foo->selectAllClassTypes(); ?>
    </div>
  </fieldset>

  <!-- Devices Found -->
  <fieldset id='devicesFoundTable'>
    <legend>Devices Found
      <span>(<?php echo $foo->numberFormatShort($foo->countThings('count_deviceID')) ?>)</span>
    </legend>
    <div class="">
      <?php $foo->selectAllDevices(); ?>
    </div>
  </fieldset>

  <!-- Stats -->
  <fieldset id='statsTable'>
    <legend>Stats</legend>
    <div class="">
      <?php $foo->tableStats(); ?>
    </div>
  </fieldset>

  <!-- Stats -->
  <fieldset id='liveCount'>
    <legend>Live Count</legend>
    <div class="">
      <h1 class='text-center'><?php echo $foo->liveObjectCounter("person",2); ?></h1>
      <!-- <h1 class='text-center'><?php echo $foo->liveObjectCounter("ALL","ALL"); ?></h1> -->
    </div>
  </fieldset>

  <!-- Class Chart -->
  <!-- <fieldset id='classChartFieldset'>
    <legend>Class Chart</legend>
    <div class="">
      <div id="chart-container">
        <canvas width="400px" height="400px" id="mycanvas-class"></canvas>
      </div>
    </div>
  </fieldset> -->

  <!-- Total Chart -->
  <!-- <fieldset id='totalChartFieldset'>
    <legend>Total Chart</legend>
    <div class="">
      <div id="chart-container-total">
        <canvas width="400px" height="400px" id="mycanvas-total"></canvas>
      </div>
    </div>
  </fieldset> -->

  <!-- Data Functions -->
  <fieldset id='dataFunctionsFieldset'>
    <legend>Data Functions</legend>
    <div class="">
      <?php
      echo Application::getAppDetails();
      echo ApplicationVersion::get();
      ?>
      <form class="" action="index.php" method="post">
        <button class='btn btn-outline-danger' type="button" name="btnTruncate" disabled>Truncate</button>
      </form>
    </div>
  </fieldset>

</div>

<!-- Chart JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>

<!-- Footer -->
<?php require_once 'partials/_footer.php'; ?>
