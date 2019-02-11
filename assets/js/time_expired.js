//System-wide max allowable time (when evaluated) that a user is allowed to
//remain unresponsive on a page.
var maxMillis = 16000;

//elapseSurvey()
//If the user sits idle on a page greater than the time allowed for that page's
//content, then this function is called by the JS of that page to call it quits
function elapseSurvey() {
  alert("Time's up");
} //END elapseSurvey()
