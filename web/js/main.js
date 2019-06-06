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
