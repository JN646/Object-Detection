<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Object Detection</title>

    <!-- Light -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <?php
    // Start session
    session_start();

    // Error
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Test Session
    if (!isset($_SESSION["darkMode"])) {
      $_SESSION["darkMode"] = 0;
    }
    ?>

    <!-- Check dark mode -->
    <?php if ($_SESSION["darkMode"] == 1): ?>
      <!-- Dark -->
      <link rel="stylesheet" href="css/dark.bootstrap.min.css">
    <?php endif; ?>

    <!-- Other CSS -->
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  </head>

  <body>
  <!-- Start Clock -->
  <body onload="startTime()">

  <!-- Nav Bar -->
  <nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Object Detection</a>
    <span class='text-white' id='clock'>Clock</span>
  </nav>
