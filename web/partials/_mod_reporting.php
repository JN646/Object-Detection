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
