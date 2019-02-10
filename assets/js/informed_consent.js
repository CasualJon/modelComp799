//Add event listener for input box of search field
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('consent_agree').onchange = updateContinueButton;
},false);

console.log("IN JS FILE");

//updateContinueButton()
//Called by event listener on Informed Consent Agreement checkbox - toggles
//disabled status of button when consent agreement is given
function updateContinueButton() {
  console.log("checked = " + document.getElementById('consent_agree').checked);
  var continueButton = document.getElementById('continue_button');
  if (document.getElementById('consent_agree').checked) {
    continueButton.disabled = false;
  }
  else {
    continueButton.disabled = true;
  }
}
