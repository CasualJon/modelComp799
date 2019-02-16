//Get the information to load onto the survey page from the server
fetchSurveyControl();


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
  document.getElementById("question_title").innerHTML = "Thank you!";
  document.getElementById("points_total").innerHTML = surveyControl.survey.score;
} //END setQuestionCountAndScore()
