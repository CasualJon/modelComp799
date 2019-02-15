//Get the information to load onto the survey page from the server
fetchSurveyControl();


//-------------------------------------------------------------------------------
//fetchSurveyControl()
//Function to make an Ajax call to a php file that will pull data from the
//server via ./support_php_files/sql_interact.php
function fetchSurveyControl() {
  jQuery.ajax({
    type:     "POST",
    url:      '../../support_files/sql_interact.php',
    dataType: 'json',
    data:     {functionname: 'get_survey_control', arguments: null},
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
  var qNum = surveyControl.survey.curr_question + 1;
  document.getElementById("question_title").innerHTML = "Question " + qNum;
  document.getElementById("points_total").innerHTML = surveyControl.survey.score;
} //END setQuestionCountAndScore()
