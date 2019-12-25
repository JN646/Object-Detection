<?php include '../partials/_header.php' ?>
<head>
	<link rel="stylesheet" href="../css/main.css">
</head>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="../js/main.js"></script>

<link rel="stylesheet" href="../css/style_boldRed.css">
<body>
	<div id='outputContainer'>
		<div id="show"></div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			setInterval(function () {
				$('#show').load('data.php')
			}, 1000);
		});
	</script>

	<!-- Launcher Button -->
	<span style='position: absolute; bottom: 10px; left: 5px;'>
		<a href='../index.php'>Back<a>
	</span>

</body>
</html>
