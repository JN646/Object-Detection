<?php
// Make the connection.
$conn = new mysqli('localhost', 'root', '', 'objectTracker2');

// Connect error catch.
if ($conn->connect_error) {
	die("Connection error: " . $conn->connect_error);
}

// Get the latest count.
$result = $conn->query("SELECT COUNT(*) as count
FROM counter
WHERE count_time
IN (SELECT MAX(count_time) FROM counter)
AND count_class = 'person' AND count_deviceID = '2'"
);

// If there are results.
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$count = $row['count'];

		// Output count.
		echo "<h1><span>{$count}</span></h1>";
	}
} else {
	// If there are no results.
	echo "<h1>N/A</h1>";
}
?>
