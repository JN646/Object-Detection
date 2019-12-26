<?php
require_once "classes/loader.class.php";

$db = new DB();

header("Content-type: image/png");

$img_width = 2000;
$img_height = 1500;

$img = imagecreatetruecolor($img_width, $img_height);

$black = imagecolorallocate($img, 0, 0, 0);
$white = imagecolorallocate($img, 255, 255, 255);
$red   = imagecolorallocate($img, 255, 0, 0);
$green = imagecolorallocate($img, 0, 255, 0);
$blue  = imagecolorallocate($img, 0, 0, 255);
$orange = imagecolorallocate($img, 255, 200, 0);

imagefill($img, 0, 0, $black);

$accounts = $db->query('SELECT * FROM `counter`')->fetchAll();

foreach ($accounts as $account) {
  switch ($account['count_class']) {
    case '0':
      $colour = $red;
      break;

    case '67':
      $colour = $blue;
      break;

    case '72':
      $colour = $orange;
      break;

    default:
      $colour = $green;
      break;
  }

  imagerectangle($img, $account['count_left']+300, $account['count_top']+300, $account['count_right'], $account['count_bottom'], $colour);
}

imagepng($img);
?>
