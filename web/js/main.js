// JS FILE
// JS Hello
console.log("JS File Loaded.")

// Hide by default
$(document).ready(function(){
  $("#classChartFieldset").hide();
  $("#totalChartFieldset").hide();
});

// Clock
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('clock').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}

// Select All Button
if (document.getElementById('doSelectAll')) {
  var selectAllCheck = document.getElementById('doSelectAll')
  var checkboxes = document.getElementsByClassName('doCheckbox')
  selectAllCheck.addEventListener("change", function() {
    for (var i = 0; i < checkboxes.length; i++) {
      if (selectAllCheck.checked == true) {
        checkboxes[i].checked = true
      }
      if (selectAllCheck.checked == false) {
        checkboxes[i].checked = false
      }
    }
  });
}

// AJAX
$(document).ready(function(){
  // Total
	$.ajax({
		url: "http://localhost/object2/ajax/data.php",
		method: "GET",
		success: function(total) {
			console.log(total);
			var count_time = [];
			var count_id = [];

			for(var i in total) {
				count_time.push(total[i].count_time);
				count_id.push(total[i].count_id);
			}

			var chartdata = {
				labels: count_time,
				datasets : [
					{
						label: 'Count',
						backgroundColor: 'rgba(0, 200, 0, 0.75)',
						borderColor: 'rgba(200, 200, 200, 0.75)',
						hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
						hoverBorderColor: 'rgba(200, 200, 200, 1)',
						data: count_id
					}
				]
			};

			var ctx = $("#mycanvas-total");

			var barGraph = new Chart(ctx, {
				type: 'line',
				data: chartdata
			});
		},
		error: function(total) {
			console.log(total);
		}
	});

  // Class
  $.ajax({
    url: "http://localhost/object2/ajax/data.php",
    method: "GET",
    success: function(data) {
      console.log(data);
      var count_class = [];
      var count = [];

      for(var i in data) {
        count_class.push(data[i].count_class);
        count.push(data[i].count);
      }

      var chartdata = {
        labels: count_class,
        datasets : [
          {
            // label: 'Count',
            backgroundColor: 'rgba(200, 0, 0, 0.75)',
            borderColor: 'rgba(200, 200, 200, 0.75)',
            hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
            hoverBorderColor: 'rgba(200, 200, 200, 1)',
            data: count
          }
        ]
      };

      var ctx = $("#mycanvas-class");

      var barGraph = new Chart(ctx, {
        type: 'pie',
        data: chartdata
      });
    },
    error: function(data) {
      console.log(data);
    }
  });
});

//  Dashboard toggle buttons
function toggleDetectedObj() {
  $("#detectedObjectAllTable").toggle();
}

function toggleClasses() {
  $("#classesFoundTable").toggle();
}

function toggleDevices() {
  $("#devicesFoundTable").toggle();
}

function toggleStats() {
  $("#statsTable").toggle();
}

function toggleLiveCount() {
  $("#liveCount").toggle();
}

function toggleClassesChart() {
  $("#classChartFieldset").toggle();
}

function toggleTotalChart() {
  $("#totalChartFieldset").toggle();
}

function toggleDataFunctions() {
  $("#dataFunctionsFieldset").toggle();
}
