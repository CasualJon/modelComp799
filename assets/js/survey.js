$(document).ready(function(){
  //Enable the option buttons (user classify vs model classify) after 10 seconds
  //(10,000ms). ***NOTE: optionDelay is controlled by /assets/js/time_expired.js
  setTimeout(enableUserSelect, optionDelay);
  //If user sits idle for > 4 minutes (240s, 240000ms), stop survey
  //***NOTE: maxMillis is controlled by /assets/js/time_expired.js
  setTimeout(elapseSurvey, maxMillis);
});

//enableUserSelect()
//Function called after appropriate period for question/inspection to enable
//page-specific user controls to respond to the question
function enableUserSelect() {
  alert("user selection enabled");
} //END enableUserSelect()
