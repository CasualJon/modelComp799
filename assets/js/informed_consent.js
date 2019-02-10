//Add event listener for input box of search field
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('consent_agree').onchange = updateContinueButton;
},false);

//updateContinueButton()
//Called by event listener on Informed Consent Agreement checkbox - toggles
//disabled status of button when consent agreement is given
function updateContinueButton() {
  var continueButton = document.getElementById('continue_button');
  if (document.getElementById('consent_agree').checked) {
    continueButton.disabled = false;
  }
  else {
    continueButton.disabled = true;
  }
}
