<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="detectedObjectLabel">Detected Objects</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link text-danger" href="functions/func_detectObjects.php?delete_all" onclick='return confirm("Are you sure you want to delete all notifications?");'>Delete All</a>
        </li>
      </ul>
      <?php $foo->selectAllTableExtended(); ?>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
