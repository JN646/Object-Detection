<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="refresh" content="5" /> -->
    <title>Object Detection</title>

    <!-- Light -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <?php
    // Start session
    session_start();
    ?>

    <!-- Check dark mode -->
    <?php if ($_SESSION["darkMode"] == 1): ?>
      <!-- Dark -->
      <link rel="stylesheet" href="css/dark.bootstrap.min.css">
    <?php endif; ?>

    <!-- Other CSS -->
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/main.css">
  </head>

  <body>
  <!-- Start Clock -->
  <body onload="startTime()">

  <!-- Nav Bar -->
  <nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Object Detection</a>
    <span class='text-white' id='clock'>Clock</span>
  </nav>
