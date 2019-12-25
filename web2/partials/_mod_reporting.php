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
      <div class='col-md-12'>
        <div class="row">
          <form class="col-md-12" action="functions/reports.php" method="POST">
            <p>Use these buttons to output CSV files of the data in the system.</p>
            <div class='row'>

              <!-- Detected Objects -->
              <div class='col'>
                <div class="card">
                  <h5 class='card-header text-center'>Detected Objects</h5>
                  <div class="card-body">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                      <div class="btn-group mr-2" role="group" aria-label="First group">
                        <button class='btn btn-outline-primary' type="submit" name="csvOutput"><i class="fas fa-download"></i> All</button>
                        <button class='btn btn-outline-primary' type="submit" name="csvTodayOutput"><i class="fas fa-download"></i> Today</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Notifications -->
              <div class='col'>
                <div class="card">
                  <h5 class='card-header text-center'>Notifications</h5>
                  <div class="card-body">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                      <div class="btn-group mr-2" role="group" aria-label="Second group">
                        <button class='btn btn-outline-primary' type="submit" name="csvNotificationOutput"><i class="fas fa-download"></i> All</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Classes -->
              <div class='col'>
                <div class="card">
                  <h5 class='card-header text-center'>Classes</h5>
                  <div class="card-body">
                    <button class='btn btn-outline-primary' type="submit" name="csvClassesOutput"><i class="fas fa-download"></i> All</button>
                  </div>
                </div>
              </div>

              <!-- Devices -->
              <div class='col'>
                <div class="card">
                  <h5 class='card-header text-center'>Devices</h5>
                  <div class="card-body">
                    <button class='btn btn-outline-primary' type="submit" name="csvDeviceOutput"><i class="fas fa-download"></i> All</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <br>

      <div class='row'>
        <!-- Block 1 -->
        <div class='col-md-6'>
          <div class="card">
            <h5 class='card-header text-center'>Date</h5>
            <div class="card-body">
              <p>Choose a start and end date and time.</p>
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
