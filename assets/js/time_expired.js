//System-wide max allowable time (when evaluated) that a user is allowed to
//remain unresponsive on a page.
var maxMillis = 16000;

//System-wide minimum time (when evaluated) that user option buttons will remain
//disabled to ensure reasonable time for question evaluation
var optionDelay = 10000;

//elapseSurvey()
//If the user sits idle on a page greater than the time allowed for that page's
//content, then this function is called by the JS of that page to call it quits
function elapseSurvey() {
  alert("Time expired");
} //END elapseSurvey()
