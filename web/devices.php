<?php
# ==============================================================================
# Imports
# ==============================================================================
require_once 'classes/class_application.php';
require_once 'classes/class_devices.php';
require_once 'partials/_header.php';
$bar = new devices();
?>
<!-- Main container -->
<div class='fluid-container'>
  <div class='col-md-12'>
    <h1 class='display-4'>Devices <span class='badge badge-secondary'><?php echo numberFormatShort($bar->countDevices('device_id')) ?></span></h1>
    <div class'row'>
      <div class='alert alert-info text-center'>Under construction coming soon.</div>
    </div>
    <div class="row">
      <div class='col-md-12'>
        <?php $bar->selectAllDevicesCRUD() ?>
      </div>
    </div>
  </div>
<!-- </div> -->

<!-- Footer -->
<?php require_once 'partials/_footer.php'; ?>
