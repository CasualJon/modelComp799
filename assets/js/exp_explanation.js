$(document).ready(function(){
  //Enable the "BEGIN" button after 10 seconds (10,000ms)
  //***NOTE: optionDelay is controlled by /assets/js/time_expired.js
  setTimeout(enableBeginButton, optionDelay);
  //If user sits idle for > 4 minutes (240s, 240000ms), stop survey
  //***NOTE: maxMillis is controlled by /assets/js/time_expired.js
  setTimeout(elapseSurvey, maxMillis);
});

//enableBeginButton()
//Function enables the button to begin the experiment
function enableBeginButton() {
  document.getElementById('continue_button').disabled = false;
} //END enableBeginButton()

//beginExperiment()
//Function called by onclick action of the BEGIN button
function beginExperiment() {
  window.location = "../../survey.php";
}
