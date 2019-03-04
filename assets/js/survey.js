//Variable for survey control data
var surveyControl = new Array();

//Fetching screen width (to adjust for mobile)
var screenWidth = window.innerWidth
  || document.documentElement.clientWidth
  || document.body.clientWidth;
var imgWidth;
//Make max image size 90% for small screens, else limit to about 1/3
if (screenWidth < 768) {
  imgWidth = screenWidth / 1.1;
  document.getElementById("score_space").setAttribute("class", "");
}
else imgWidth = screenWidth / 3.4;

//Consts for string use across functions
const ml_classify = "ml_classify";
const user_classify = "user_classify";

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
                  renderNextQuestion();
                }
                else {
                  console.log(obj.error);
                }
              }
  });

  return;
}  //END fetchSurveyControl()


//renderNextQuestion()
//Function that takes surveyControl object information to build
function renderNextQuestion() {
  //TODO - remove debug code
  console.log(surveyControl);

  //If survey responds that an intervention is next or the survey is completed,
  //redirect to appropriate pages via window.location
  if ('intervention' in surveyControl) {
    location.replace("../../intervention_0.php");
    return;
  }
  else if ('complete' in surveyControl) {
    location.replace("../../thank_you.php");
    return;
  }

  //Set the header information
  var qNum = surveyControl.survey.curr_question + 1;
  document.getElementById("question_title").innerHTML = "Question " + qNum;
  document.getElementById("points_total").innerHTML = surveyControl.survey.score;

  //Set the question_space information
  var questionSpace = document.getElementById('question_space');

  while(questionSpace.hasChildNodes()) {
    questionSpace.removeChild(questionSpace.firstChild);
  }

  //Load a spinner as a placeholder for the question image
  var loadingDisplay = document.createElement("i");
  loadingDisplay.setAttribute("class", "fa fa-spinner fa-spin");
  questionSpace.appendChild(loadingDisplay);

  //Set the respond_space informaiton
  var respondSpace = document.getElementById('respond_space');

  while (respondSpace.hasChildNodes()) {
    respondSpace.removeChild(respondSpace.firstChild);
  }

  var textData = document.createElement('h5');
  textData.innerHTML = "Choose how to classify this image.<br />";
  respondSpace.appendChild(textData);

  var userClassifyBtn = document.createElement("button");
  userClassifyBtn.disabled = true;
  userClassifyBtn.setAttribute("id", user_classify);
  userClassifyBtn.setAttribute("class", "btn btn-outline-primary btn-block btn-lg");
  userClassifyBtn.setAttribute("onclick", "selectionUserClassify()");
  userClassifyBtn.innerHTML = "Human Classify";
  respondSpace.appendChild(userClassifyBtn);

  var mlClassifyBtn = document.createElement("button");
  mlClassifyBtn.disabled = true;
  mlClassifyBtn.setAttribute("id", ml_classify);
  mlClassifyBtn.setAttribute("class", "btn btn-outline-success btn-block btn-lg");
  mlClassifyBtn.setAttribute("onclick", "selectionMLClassify()");
  mlClassifyBtn.innerHTML = "Model Classify";
  respondSpace.appendChild(mlClassifyBtn);

  //Load the image after 2 seconds of delay
  setTimeout(loadImage, 2000);
  //Enable the buttons for user selection after 3.5 seconds
  setTimeout(enableUserSelect, 2750);
} //END renderNextQuestion()


//enableUserSelect()
//Function called after appropriate period for question/inspection to enable
//page-specific user controls to respond to the question
function loadImage() {
  var questionSpace = document.getElementById('question_space');

  while(questionSpace.hasChildNodes()) {
    questionSpace.removeChild(questionSpace.firstChild);
  }

  var imageDisplay = document.createElement("img");
  imageDisplay.setAttribute("src", surveyControl.question_path);
  imageDisplay.setAttribute("width", imgWidth);
  questionSpace.appendChild(imageDisplay);
} //END loadImage()


//enableUserSelect()
//Function called after appropriate period for question/inspection to enable
//page-specific user controls to respond to the question
function enableUserSelect() {
  var usrBtn = document.getElementById(user_classify);
  var mlBtn = document.getElementById(ml_classify);
  usrBtn.setAttribute("class", "btn btn-primary btn-block btn-lg");
  usrBtn.disabled = false;
  mlBtn.setAttribute("class", "btn btn-success btn-block btn-lg");
  mlBtn.disabled = false;
} //END enableUserSelect()


//selectionUserClassify()
function selectionUserClassify() {
  //Set the respond_space informaiton
  var respondSpace = document.getElementById('respond_space');

  while (respondSpace.hasChildNodes()) {
    respondSpace.removeChild(respondSpace.firstChild);
  }

  var textData = document.createElement('h5');
  textData.innerHTML = "Which option best classifies this image?<br />";
  respondSpace.appendChild(textData);

  for (var i = 0; i < surveyControl.sel_options.length; i++) {
    var userSelectBtn = document.createElement("button");
    userSelectBtn.setAttribute("class", "btn btn-outline-primary btn-block btn-lg");
    var buttonCall = "executeUserSelection(" + i + ")";
    userSelectBtn.setAttribute("onclick", buttonCall);
    var optionId = "classifyButton" + i;
    userSelectBtn.setAttribute("id", optionId);
    userSelectBtn.innerHTML = surveyControl.sel_options[i];
    userSelectBtn.disabled = true;
    respondSpace.appendChild(userSelectBtn);
  }

  var br1 = document.createElement("br");
  var br2 = document.createElement("br");
  respondSpace.appendChild(br1);
  respondSpace.appendChild(br2);

  var mlClassifyBtn = document.createElement("button");
  mlClassifyBtn.disabled = true;
  mlClassifyBtn.setAttribute("id", ml_classify);
  mlClassifyBtn.setAttribute("class", "btn btn-outline-success btn-block btn-lg");
  mlClassifyBtn.setAttribute("onclick", "selectionMLClassify()");
  mlClassifyBtn.innerHTML = "Model Classify";
  respondSpace.appendChild(mlClassifyBtn);

  //Enable the buttons for user selection after 3.5 seconds
  setTimeout(enableClassifySelect, 750);
} //END selectionUserClassify()


//enableClassifySelect()
//Function called after appropriate period for question/inspection to enable
//page-specific user controls to respond to the question
function enableClassifySelect() {
  var prefix = "classifyButton";
  for (var i = 0; i < surveyControl.sel_options.length; i++) {
    var id = prefix + i;
    document.getElementById(id).setAttribute("class", "btn btn-primary btn-block btn-lg");
    document.getElementById(id).disabled = false;
  }
  document.getElementById('ml_classify').setAttribute("class", "btn btn-success btn-block btn-lg");
  document.getElementById('ml_classify').disabled = false;
} //END enableClassifySelect()


//selectionMLClassify()
function selectionMLClassify() {
  //Args: 1 for ML classify
  //      null becasue no user selection
  var args = [1, null];
  jQuery.ajax({
    type:     "POST",
    url:      '../../support_files/sql_interact.php',
    dataType: 'json',
    data:     {functionname: 'get_next_question', arguments: args},
    error:    function(a, b, c) {
                console.log("jQuery.ajax could not execute php file.");
              },
    success:  function(obj) {
                if (!('error' in obj)) {
                  surveyControl = obj;
                  //Callback
                  renderNextQuestion();
                }
                else {
                  console.log(obj.error);
                }
              }
  });
} //END selectionMLClassify()


//executeUserSelection()
function executeUserSelection(val) {
  //Args: 0 for User classify
  //      val passed in from button clicked as option selected
  var args = [0, val];
  jQuery.ajax({
    type:     "POST",
    url:      '../../support_files/sql_interact.php',
    dataType: 'json',
    data:     {functionname: 'get_next_question', arguments: args},
    error:    function(a, b, c) {
                console.log("jQuery.ajax could not execute php file.");
              },
    success:  function(obj) {
                if (!('error' in obj)) {
                  surveyControl = obj;
                  //Callback
                  renderNextQuestion();
                }
                else {
                  console.log(obj.error);
                }
              }
  });
} //END executeUserSelection()
