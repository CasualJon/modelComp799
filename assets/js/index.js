$(document).ready(function(){
  //Enable the "BEGIN" button after 10 seconds (10,000ms)
  //THIS IS TO WEED OUT PEOPLE RUSHING THROUGH WITHOUT READING THE DIRECTIONS
  //THE DIRECTIONS WILL ASK RESPONDANTS NOT TO CHECK THE CHECKBOX
  setTimeout(enableBeginButton, 10000);
});

//enableBeginButton()
//Function enables the button to begin the experiment
function enableBeginButton() {
  document.getElementById('begin_button').disabled = false;
} //END enableBeginButton()

//beginExperiment()
//Function called by onclick action of the BEGIN button
function beginExperiment() {
  window.location = "../../survey.php";
}


//updateContinueButton()
//Called by event listener on Informed Consent Agreement checkbox - toggles
//disabled status of button when consent agreement is given
function updateContinueButton() {
  var continueButton = document.getElementById('begin_button');
  if (document.getElementById('exp_acknowledge').checked) {
    continueButton.disabled = false;
  }
} //END updateContinueButton()
