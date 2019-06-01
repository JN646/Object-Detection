<?php
# ==============================================================================
# Imports
# ==============================================================================
require_once 'classes/class_objectDetection.php';
require_once 'classes/class_application.php';
require_once 'classes/class_devices.php';
require_once 'classes/common.php';
require_once 'partials/_header.php';

// Create new object.
$foo = new ObjectDetection();
?>
<!-- Main container -->
<div class='fluid-container'>
  <div class='col-md-12'>
    <h1 class='display-4'>Dashboard</h1>

    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="counter/index.php">Counter</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="reporttest.php">Report Test</a>
      </li>
    </ul>

    <div class="row Row1">
        <div class="col-sm-12 col-md-6">
          <!-- CSV Reporting -->
          <fieldset class='fieldsetMain' id='csvExport'>
            <legend class='legendMain'>Reporting</legend>
            <!-- Button Toolbar -->
            <form class="" action="functions/reports.php" method="POST">
              <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2" role="group" aria-label="First group">
                  <button class='btn btn-outline-primary' type="submit" name="csvOutput">All</button>
                  <button class='btn btn-outline-primary' type="submit" name="csvTodayOutput">Today</button>
                </div>
              </div>
            </form>
            <br>
            <div class='row'>
              <!-- Block 1 -->
              <div class='col'>
                <fieldset class='fieldsetInner'>
                  <legend class='legendInner'>Date</legend>
                  <form class="" action="functions/reports.php" method="POST">
                    <div class="row">
                      <div class="col-md-12 col-lg-5">
                        <input type="datetime-local" class='form-control' name='dateSelectStart' value="<?php echo date("Y-m-d H:i:s",$timestamp); ?>"/>
                      </div>
                      <div class="col-md-12 col-lg-5">
                        <input type="datetime-local" class='form-control' name='dateSelectEnd' value="<?php echo date("Y-m-d H:i:s",$timestamp); ?>"/>
                      </div>
                      <div class="col-md-12 col-lg-2">
                        <button class='form-control btn btn-outline-success' type="submit" name="csvDateSelectGo">Go</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
              </div>

              <!-- Block 2 -->
              <div class='col'>
                <fieldset class='fieldsetInner'>
                  <legend class='legendInner'>Something Else</legend>
                </fieldset>
              </div>
            </div>
          </fieldset>

          <!-- Detected Object Table -->
          <fieldset class='fieldsetMain' id='detectedObjectAllTable'>
            <legend class='legendMain'>Detected Objects
              <span>(<?php echo numberFormatShort($foo->countThings("All")) ?>)</span>
            </legend>
            <div id="detectedObjectAllTableInner">
              <?php $foo->selectAllTable(); ?>
            </div>
          </fieldset>
        </div>

        <!-- Second Column -->
        <div class="col-sm-12 col-md-6">
          <div class='row'>
            <div class="col-sm-12 col-md-6">
              <!-- Stats -->
              <fieldset class='fieldsetMain' id='statsTable'>
                <legend class='legendMain'>Stats</legend>
                <div class="">
                  <?php $foo->tableStats(); ?>
                </div>
              </fieldset>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- Live Count -->
              <fieldset class='fieldsetMain' id='liveCount'>
                <legend class='legendMain'>Live Count</legend>
                <div class="">
                  <!-- <h1 class='text-center'><?php echo $foo->liveObjectCounter("person",2); ?></h1> -->
                  <h1 class='display-2 text-center'><?php echo $foo->liveObjectCounter("ALL","ALL"); ?></h1>
                </div>
              </fieldset>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- Classes Found -->
              <fieldset class='fieldsetMain' id='classesFoundTable'>
                <legend class='legendMain'>Classes Found
                  <span>(<?php echo numberFormatShort($foo->countThings('count_class')) ?>)</span>
                </legend>
                <div class="">
                  <?php $foo->selectAllClassTypes(); ?>
                </div>
              </fieldset>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- Data Functions -->
              <fieldset class='fieldsetMain' id='dataFunctionsFieldset'>
                <legend class='legendMain'>Data Functions</legend>
                <div class="">
                  <?php echo Application::getAppDetails(); ?>
                  <form class="" action="index.php" method="post">
                    <button class='btn btn-outline-danger' type="button" name="btnTruncate" disabled>Truncate</button>
                  </form>
                </div>
              </fieldset>
            </div>
          </div>

          <!-- Devices Found -->
          <fieldset class='fieldsetMain' id='devicesFoundTable'>
            <a href='devices.php'><legend class='legendMain'>Devices Found
              <span>(<?php echo numberFormatShort($foo->countThings('count_deviceID')) ?>)</span>
            </legend></a>
            <div class="">
            </div>
          </fieldset>
        </div> <!-- Col 6 -->
      </div> <!-- Row -->
    </div>

<!-- Footer -->
<?php require_once 'partials/_footer.php'; ?>
