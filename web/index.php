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

<script type="text/javascript">
  $(document).ready(function(){
    refreshTable();
  });

  function refreshTable(){
    $('#div1').load('demo.php', function(){
      console.log("Test");
      setTimeout(refreshTable, 1000);
    });
  }
</script>
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
          <div id='csvExport' class="card">
            <h5 class='card-header text-center'>Reporting</h5>
            <div class="card-body">
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
                  <div class="card">
                    <h5 class='card-header text-center'>Date</h5>
                    <div class="card-body">
                      <form class="" action="functions/reports.php" method="POST">
                        <div class="row">
                          <div class="col-md-12">
                            <input type="datetime-local" class='form-control' name='dateSelectStart' value="<?php echo date("Y-m-d H:i:s",$timestamp); ?>"/>
                          </div>
                          <div class="col-md-12">
                            <input type="datetime-local" class='form-control' name='dateSelectEnd' value="<?php echo date("Y-m-d H:i:s",$timestamp); ?>"/>
                          </div>
                          <div class="col-md-12">
                            <button class='form-control btn btn-outline-success' type="submit" name="csvDateSelectGo">Go</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- Block 2 -->
                <div class='col'>
                  <div class="card">
                    <h5 class='card-header text-center'>Something Else</h5>
                    <div class="card-body">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Detected Object Table -->
          <div id='detectedObjectAllTable' class="card">
            <h5 class='card-header text-center'>Detected Objects
              <span class='badge badge-secondary'><?php echo numberFormatShort($foo->countThings("All")) ?></span></h5>
            <div class="card-body">
              <div id="detectedObjectAllTableInner">
                <?php $foo->selectAllTable(); ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Second Column -->
        <div class="col-sm-12 col-md-6">
          <div class='row'>
            <div class="col-sm-12 col-md-6">

              <!-- Stats -->
              <div id='statsTable' class="card">
                <h5 class='card-header text-center'>Stats</h5>
                <div class="card-body">
                  <?php $foo->tableStats(); ?>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- Live Count -->
              <div id='liveCount' class="card">
                <h5 class='card-header text-center'>Live Count</h5>
                <div class="card-body">
                  <h1 class='display-2 text-center'><?php echo $foo->liveObjectCounter("ALL","ALL"); ?></h1>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- Classes Found -->
              <div id='classesFoundTable' class="card">
                <h5 class='card-header text-center'>Classes Found
                  <span class='badge badge-secondary'><?php echo numberFormatShort($foo->countThings('count_class')) ?></span></h5>
                <div class="card-body">
                  <?php $foo->selectAllClassTypes(); ?>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- Global Settiings -->
              <div id='dataFunctionsFieldset' class="card">
                <h5 class='card-header text-center'>Global Settings</h5>
                <div class="card-body">
                  <div id='div1' class=""></div>
                  <form class="" action="functions/global.php" method="POST">
                    <button class='btn btn-outline-primary' type="submit" name="btnDark"><i class="fas fa-moon"></i></button>
                    <button class='btn btn-outline-danger' type="button" name="btnTruncate" disabled>Truncate</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Devices Found -->
          <div id='devicesFoundTable' class="card">
            <?php $bar = new devices(); ?>
            <h5 class='card-header text-center'>Devices Found
              <span class='badge badge-secondary'><?php echo numberFormatShort($bar->countDevices('device_id')) ?></span></h5>
            <div class="card-body">
              <?php $bar->selectAllDevices() ?>
            </div>
          </div>
        </div> <!-- Col 6 -->
      </div> <!-- Row -->
    </div>

<!-- Footer -->
<?php require_once 'partials/_footer.php'; ?>
