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
          <form class="" action="functions/reports.php" method="POST">
            <p>Use these buttons to output CSV files of the data in the system.</p>
            <div class='row'>
              <div class='col'>
                <h3>Detected Objects</h3>
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                  <div class="btn-group mr-2" role="group" aria-label="First group">
                    <button class='btn btn-outline-primary' type="submit" name="csvOutput">All</button>
                    <button class='btn btn-outline-primary' type="submit" name="csvTodayOutput">Today</button>
                  </div>
                </div>
              </div>

              <div class='col'>
                <h3>Notifications</h3>
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                  <div class="btn-group mr-2" role="group" aria-label="Second group">
                    <button class='btn btn-outline-primary' type="submit" name="csvNotificationOutput">All</button>
                  </div>
                </div>
              </div>

              <div class='col'>
                <h3>Classes</h3>
              </div>

              <div class='col'>
                <h3>Devices</h3>
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
