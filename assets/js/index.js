//Make a file-wide var to hold the Begin Button element
var beginButton = document.getElementById('begin_button');

$(document).ready(function(){
  //Enable the Begin Button automatically after 8 seconds (8,000ms)
  setTimeout(enableBeginButton, 8000);
});

//enableBeginButton()
//Function enables the button to begin the experiment
function enableBeginButton() {
  beginButton.disabled = false;
} //END enableBeginButton()
