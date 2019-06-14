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
            <h5 class='card-header text-center'>Detected Objects
              <span class='badge badge-secondary'><?php echo numberFormatShort($foo->countThings("All")) ?></span>
            </h5>
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
              <!-- Global Settiings -->
              <div id='dataFunctionsFieldset' class="card">
                <h5 class='card-header text-center'>Global Settings</h5>
                <div class="card-body">
                  <form class="" action="functions/global.php" method="POST">
                    <button class='btn btn-outline-primary' type="submit" name="btnDark"><i class="fas fa-moon"></i></button>
                    <button class='btn btn-outline-danger' type="button" name="btnTruncate" disabled>Truncate</button>
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
                        <button class='btn btn-outline-primary' type="submit" name="csvOutput">All</button>
                        <button class='btn btn-outline-primary' type="submit" name="csvTodayOutput">Today</button>
                        <button class='btn btn-outline-primary' type="submit" name="csvNotificationOutput">Notif.</button>
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
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="notificationLabel">Notifications</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php
            // $baz->createNotification(1,2,"This is a new test with a function.",2);
            // $baz->deleteNotification(11);
            $baz->selectAllNotifications();
            ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Classes Found Modal -->
    <div class="modal fade" id="classesFoundModal" tabindex="-1" role="dialog" aria-labelledby="classesFoundLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="classesFoundLabel">Classes Found</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php $foo->selectAllClassTypes(0); ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Devices Found Modal -->
    <div class="modal fade" id="devicesFoundModal" tabindex="-1" role="dialog" aria-labelledby="devicesFoundLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="devicesFoundLabel">Devices Found</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php $bar->selectAllDevicesCRUD() ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Counter Modal -->
    <div class="modal fade" id="counterModal" tabindex="-1" role="dialog" aria-labelledby="counterLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="counterLabel">Counter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h1 id='counterLarge' class='display-1 text-center'><?php echo $foo->liveObjectCounter("ALL","ALL"); ?></h1>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Reporting Modal -->
    <div class="modal fade" id="reportingModal" tabindex="-1" role="dialog" aria-labelledby="reportingLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportingLabel">Reporting</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- CSV Reporting -->
            <div class='row'>
              <!-- Block 1 -->
              <div class='col'>
                <div class="card">
                  <h5 class='card-header text-center'>Date</h5>
                  <div class="card-body">
                    <form class="" action="functions/reports.php" method="POST">
                      <div class="row">
                        <div class="col-md-12">
                          <input type="datetime-local" class='form-control' name='dateSelectStart' value=""/>
                        </div>
                        <div class="col-md-12">
                          <input type="datetime-local" class='form-control' name='dateSelectEnd' value=""/>
                        </div>
                        <div class="col-md-12">
                          <button class='form-control btn btn-outline-success' type="submit" name="csvDateSelectGo">Go</button>
                        </div>
                      </div>
                    </form>
                  </div> <!-- Card Body -->
                </div> <!-- Card -->
              </div> <!-- Col -->
            </div> <!-- Row -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      // Table Sorting
      const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

      const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
        v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

      // do the work...
      document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
        const table = th.closest('table');
        Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
            .forEach(tr => table.appendChild(tr) );
      })));

      // Filter table
      function myFunction() {
        // Set Variables
        var input, filter, table, tr, td, i, txtValue;

        // Map to IDs
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("detectedObjectAllTable");
        tr = table.getElementsByTagName("tr");

        // Loop
        for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[3];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
            } else {
              tr[i].style.display = "none";
            }
          }
        }
      }
    </script>

<!-- Footer -->
<?php require_once 'partials/_footer.php'; ?>
