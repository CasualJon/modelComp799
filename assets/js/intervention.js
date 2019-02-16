//Get the information to load onto the survey page from the server
fetchSurveyControl();

//Make a file-wide var to hold the Begin Button element
var continueButton = document.getElementById('continue_button');

$(document).ready(function(){
  //Enable the Begin Button automatically after 12 seconds (12,000ms)
  setTimeout(enableContinueButton, 8000);
});


//-------------------------------------------------------------------------------
//fetchSurveyControl()
//Function to make an Ajax call to a php file that will pull data from the
//server via ./support_php_files/sql_interact.php
function fetchSurveyControl() {
  args = ['post_intervention'];
  jQuery.ajax({
    type:     "POST",
    url:      '../../support_files/sql_interact.php',
    dataType: 'json',
    data:     {functionname: 'get_survey_control', arguments: args},
    error:    function(a, b, c) {
                console.log("jQuery.ajax could not execute php file.");
              },
    success:  function(obj) {
                if (!('error' in obj)) {
                  surveyControl = obj;
                  //Callback
                  setQuestionCountAndScore();
                }
                else {
                  console.log(obj.error);
                }
              }
  });

  return;
}  //END fetchSurveyControl()


//setQuestionCountAndScore()
//Set the header information
function setQuestionCountAndScore() {
  //curr_question advanced prior to triggering intervention, so do not add 1 here
  var qNum = surveyControl.survey.curr_question;
  document.getElementById("question_title").innerHTML = "Question " + qNum;
  document.getElementById("points_total").innerHTML = surveyControl.survey.score;
} //END setQuestionCountAndScore()


//enableContinueButton()
//Function enables the button to continue the experiment
function enableContinueButton() {
  continueButton.disabled = false;
} //END enableContinueButton()
