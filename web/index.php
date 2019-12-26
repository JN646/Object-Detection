<?php require_once("views/partials/_header.php"); ?>
<?php $db = new DB(); ?>

<div class="container-fluid">
  <h1>Object Tracker 2020</h1>
  <div class="row">
      <!-- Detected Objects -->
      <div class="col my-2">
        <div class='card shadow'>
          <h5 class='card-header'>Detected Objects
            <button type="button" data-target="#doCard" data-toggle="collapse" class="close" aria-label="Close">
              <span aria-hidden="true"><i class="far fa-xs fa-window-minimize"></i></span>
            </button>
          </h5>
          <div id='doCard' class="card-body collapse show">
            <?php $accounts = $db->query('SELECT * FROM `counter` INNER JOIN `class_types` ON `class_number` = `count_class`')->fetchAll(); ?>
            <?php if ($accounts): ?>
              <table id='doTable' class='table table-bordered table-sm'>
                <thead>
                  <?php
                  $headers = array("#","Time","Class","Conf.");
                  for ($i=0; $i < COUNT($headers); $i++) { echo "<th class='text-center'>{$headers[$i]}</th>";}
                  ?>
                </thead>
              </table>
            <?php else: ?>
              <div class="alert alert-info text-center">There are no detected objects</div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Devices -->
      <div class="col my-2">
        <div class='card shadow'>
          <h5 class='card-header'>Devices
            <button type="button" data-target="#deviceCard" data-toggle="collapse" class="close" aria-label="Close">
              <span aria-hidden="true"><i class="far fa-xs fa-window-minimize"></i></span>
            </button>
          </h5>
          <div id='deviceCard' class="card-body collapse show">
            <?php $accounts = $db->query('SELECT * FROM `devices`')->fetchAll(); ?>
            <?php if ($accounts): ?>
              <table id='deviceTable' class='table table-bordered table-sm'>
                <thead>
                  <?php
                  $headers = array("#","Name","Location","IP","Version","Threshold","Class",);
                  for ($i=0; $i < COUNT($headers); $i++) { echo "<th class='text-center'>{$headers[$i]}</th>";}
                  ?>
                </thead>
                <tbody>
                  <?php foreach ($accounts as $account): ?>
                    <tr>
                      <td class='text-center'><?= $account["device_id"]; ?></td>
                      <td><?= $account["device_name"]; ?></td>
                      <td><?= $account["device_location"]; ?></td>
                      <td><?= $account["device_ip"]; ?></td>
                      <td><?= $account["device_clientVersion"]; ?></td>
                      <td><?= $account["device_confidenceThreshold"]; ?></td>
                      <td><?= $account["device_classToDetect"]; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <div class="alert alert-info text-center">There are no detected objects</div>
            <?php endif; ?>
          </div>
        </div>
      </div>

  </div>
</div>

<script type="text/javascript">
  $(document).ready(function () {
    $('#doTable').DataTable({
      ajax: 'counter.php',
      columns: [
        { "data": "count_id" },
        { "data": "count_time" },
        { "data": "class_name" },
        { "data": "count_confidence" }
      ],
      paging: false,
      scrollY: 700,
      order: [[0, "desc"]]
    });

    $('#deviceTable').DataTable({
      paging: false,
      order: [[0, "desc"]]
    });
  });

    // $('#doTable').DataTable({
      // paging: false,
      // scrollY: 700,
      // order: [[0, "desc"]]
    // });

</script>

<script>

</script>

<?php require_once("views/partials/_footer.php"); ?>
