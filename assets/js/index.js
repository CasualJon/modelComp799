//Make a file-wide var to hold the Begin Button element
var beginButton = document.getElementById('begin_button');

$(document).ready(function(){
  //Enable the Begin Button automatically after 8 seconds (8,000ms)
  setTimeout(enableBeginButton, 8000);
});

//Add Event Listener for checkbox... This will enable the Begin Button
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('exp_acknowledge').onchange = enableBeginButton;
},false);


//enableBeginButton()
//Function enables the button to begin the experiment
function enableBeginButton() {
  beginButton.disabled = false;
} //END enableBeginButton()
