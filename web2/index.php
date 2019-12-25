<?php
# ==============================================================================
# Imports
# ==============================================================================
require_once 'classes/class_objectDetection.php';
require_once 'classes/class_application.php';
require_once 'classes/class_devices.php';
require_once 'classes/class_notification.php';
require_once 'classes/common.php';
require_once 'partials/_header.php';

// Create new object.
$foo = new ObjectDetection();
$baz = new notification();
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
          <div id='notificationPane' class="card">
            <a data-toggle="modal" data-target="#notificationModal">
              <h5 class='card-header text-center'>Notifications
                <span class='badge badge-secondary'><?php echo numberFormatShort($baz->countThings("All")) ?></span>
              </h5>
            </a>
            <div class="card-body">
              <?php $baz->selectAllNotifications() ?>
            </div>
          </div>

          <!-- Detected Object Table -->
          <div id='detectedObjectAllTable' class="card">
            <a data-toggle="modal" data-target="#detectedObjectModal">
              <h5 class='card-header text-center'>Detected Objects
                <span class='badge badge-secondary'><?php echo numberFormatShort($foo->countThings("All")) ?></span>
              </h5>
            </a>
            <div class="card-body">
              <div id="detectedObjectAllTableInner">
                <input class='form-control' type="text" id="myInput" onkeyup="myFunction()" placeholder="Filter classes.." title="Filter classes">
                <br>
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
                <a data-toggle="modal" data-target="#counterModal">
                  <h5 class='card-header text-center'>Live Count</h5>
                </a>
                <div class="card-body">
                  <h1 class='display-2 text-center'><?php echo $foo->liveObjectCounter("ALL","ALL"); ?></h1>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- Classes Found -->
              <div id='classesFoundTable' class="card">
                <a data-toggle="modal" data-target="#classesFoundModal">
                  <h5 class='card-header text-center'>Classes Found
                    <span class='badge badge-secondary'><?php echo numberFormatShort($foo->countThings('count_class')) ?></span>
                  </h5>
                </a>
                <div class="card-body">
                  <?php $foo->selectAllClassTypes(5); ?>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- Global Settings -->
              <div id='dataFunctionsFieldset' class="card">
                <h5 class='card-header text-center'>Global Settings</h5>
                <div class="card-body">
                  <form class="" action="functions/global.php" method="POST">
                    <button class='btn btn-outline-primary' type="submit" name="btnDark"><i class="fas fa-moon"></i></button>
                    <button class='btn btn-outline-primary' type="submit" name="btnImportDatabase" onclick='return confirm("Are you sure you want to import the database?");'><i class="fas fa-database"></i></button>
                    <button class='btn btn-outline-danger' type="submit" name="btnTruncateCounterDatabase" onclick='return confirm("Are you sure you want to truncate the database?");'>Truncate</button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <!-- CSV Reporting -->
              <div id='csvExport' class="card">
                <a data-toggle="modal" data-target="#reportingModal">
                  <h5 class='card-header text-center'>Reporting</h5>
                </a>
                <div class="card-body">
                  <!-- Button Toolbar -->
                  <form class="" action="functions/reports.php" method="POST">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                      <div class="btn-group mr-2" role="group" aria-label="First group">
                        <button class='btn btn-outline-primary' type="submit" name="csvOutput">Objects</button>
                        <button class='btn btn-outline-primary' type="submit" name="csvTodayOutput">Today</button>
                      </div>
                      <div class="btn-group mr-2" role="group" aria-label="Second group">
                        <button class='btn btn-outline-primary' type="submit" name="csvNotificationOutput">Notif.</button>
                      </div>
                      <div class="btn-group mr-2" role="group" aria-label="Third group">
                        <button class='btn btn-outline-primary' type="submit" name="csvClassesOutput">Classes</button>
                      </div>
                      <div class="btn-group mr-2" role="group" aria-label="Fourth group">
                        <button class='btn btn-outline-primary' type="submit" name="csvDeviceOutput">Devices</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Devices Found -->
          <div id='devicesFoundTable' class="card">
            <?php $bar = new devices(); ?>
            <a data-toggle="modal" data-target="#devicesFoundModal">
              <h5 class='card-header text-center'>
                Devices Found
                <span class='badge badge-secondary'><?php echo numberFormatShort($bar->countDevices('device_id')) ?></span>
              </h5>
            </a>
            <div class="card-body">
              <?php $bar->selectAllDevices() ?>
            </div>
          </div>
        </div> <!-- Col 6 -->
      </div> <!-- Row -->
    </div>

    <!-- Notifcation Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationLabel" aria-hidden="true">
      <?php include 'partials/_mod_notification.php' ?>
    </div>

    <!-- Classes Found Modal -->
    <div class="modal fade" id="classesFoundModal" tabindex="-1" role="dialog" aria-labelledby="classesFoundLabel" aria-hidden="true">
      <?php include 'partials/_mod_classes.php' ?>
    </div>

    <!-- Devices Found Modal -->
    <div class="modal fade" id="devicesFoundModal" tabindex="-1" role="dialog" aria-labelledby="devicesFoundLabel" aria-hidden="true">
      <?php include 'partials/_mod_devices.php' ?>
    </div>

    <!-- Counter Modal -->
    <div class="modal fade" id="counterModal" tabindex="-1" role="dialog" aria-labelledby="counterLabel" aria-hidden="true">
      <?php include 'partials/_mod_counter.php' ?>
    </div>

    <!-- Detect Object Modal -->
    <div class="modal fade" id="detectedObjectModal" tabindex="-1" role="dialog" aria-labelledby="detectedObjectLabel" aria-hidden="true">
      <?php include 'partials/_mod_detectedObjects.php' ?>
    </div>

    <!-- Reporting Modal -->
    <div class="modal fade" id="reportingModal" tabindex="-1" role="dialog" aria-labelledby="reportingLabel" aria-hidden="true">
      <?php include 'partials/_mod_reporting.php' ?>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#detectedObjectAllTable').DataTable();
    } );
    </script>

<!-- Footer -->
<?php require_once 'partials/_footer.php'; ?>
