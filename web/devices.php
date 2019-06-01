<?php
# ==============================================================================
# Imports
# ==============================================================================
require_once 'classes/class_application.php';
require_once 'classes/class_devices.php';
require_once 'partials/_header.php';
?>
<!-- Main container -->
<div class='fluid-container'>
  <div class='col-md-12'>
    <h1 class='display-4'>Devices</h1>
    <div class'row'>
      <p>Device management screen.</p>
    </div>
    <div class="row">
      <?php $bar = new devices(); ?>
      <?php $bar->selectAllDevicesCRUD() ?>
    </div>
  </div>
</div>

<!-- Footer -->
<?php require_once '../partials/_footer.php'; ?>
