// JS FILE
// JS Hello
console.log("JS File Loaded.")

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

function toggleDataFunctions() {
  $("#dataFunctionsFieldset").toggle();
}

// Table Sorting
function sortTable(n) {
  console.log("Sort Table Loaded.")
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("detectedObjectAllTable");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
