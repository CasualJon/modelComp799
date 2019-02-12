$(document).ready(function(){
  //Add Event Listener for checkbox... This will enable the Begin Button
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('exp_acknowledge').onchange = enableBeginButton;
  },false);

  //Enable the Begin Button automatically after 12 seconds (12,000ms)
  //THIS IS TO WEED OUT PEOPLE RUSHING THROUGH WITHOUT READING THE DIRECTIONS
  //THE DIRECTIONS WILL ASK RESPONDANTS NOT TO CHECK THE CHECKBOX
  setTimeout(enableBeginButton, 12000);
});

//enableBeginButton()
//Function enables the button to begin the experiment
function enableBeginButton() {
  var beginButton = document.getElementById('begin_button');
  beginButton.disabled = false;
} //END enableBeginButton()

//beginExperiment()
//Function called by onclick action of the BEGIN button
function beginExperiment() {
  window.location = "../../survey.php";
}
