$(document).ready(function(){
  //Enable the "BEGIN" button after 10 seconds (10,000ms)
  setTimeout(enableBeginButton, 10000);
  //If user sits idle for > 6 minutes (360s, 360000ms), stop the survey
  setTimeout(elapseSurvey, 15000);
});

//enableBeginButton()
//Function enables the button to begin the experiment
function enableBeginButton() {
  document.getElementById('continue_button').disabled = false;
} //END enableBeginButton()
