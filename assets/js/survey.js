//Variable for survey control data
var surveyControl = new Array();

var timerDone = false;
var choicesMade = new Array();
var selectionCount = 0;
var continueButton = document.getElementById('continue_button');

//Get the information to load onto the survey page from the server
fetchSurveyControl();

//Engage the minimum timer on the continue button
$(document).ready(function(){
  //Require at least 8 seconds (8,000ms) of time on the education screen
  setTimeout(completeTimer, 8000);
});

var seconds = 0;
setInterval(function() {++seconds;}, 1000);

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
                console.log("fetchSurveyControl() -> jQuery.ajax could not execute php file.");
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
    location.replace(surveyControl.intervention);
    return;
  }
  else if ('complete' in surveyControl) {
    location.replace("../../thank_you.php");
    return;
  }

  //Set the question_space information
  var questionSpace = document.getElementById('question_space');

  while(questionSpace.hasChildNodes()) {
    questionSpace.removeChild(questionSpace.firstChild);
  }

  //Load a spinner as a placeholder for the question image
  var loadingDisplay = document.createElement("i");
  loadingDisplay.setAttribute("class", "fa fa-spinner fa-spin");
  questionSpace.appendChild(loadingDisplay);

  loadImages();
} //END renderNextQuestion()


//loadImages()
function loadImages() {
  var tableContainer = document.createElement('div');
  tableContainer.setAttribute("class", "table-responsive")
  var tableEle = document.createElement('table');
  tableEle.setAttribute("class", "table borderless");
  tableContainer.appendChild(tableEle);
  var tbodyEle = document.createElement('tbody');
  tableEle.appendChild(tbodyEle);

  for (var i = 0; i < 4; i++) {
    var thEle = document.createElement('th');
    thEle.setAttribute("style", "width: 25%");
    tbodyEle.appendChild(thEle);
    var trEle = document.createElement('tr');
    trEle.setAttribute("class", "text-center");
    tbodyEle.appendChild(trEle);
    for (var j = 0; j < 4; j++) {
      var nameAddition = "_" + ((i * 4) + j);
      var tdEle = document.createElement('td');
      trEle.appendChild(tdEle);
      var labelEle = document.createElement('label');
      labelEle.setAttribute("class", "image-checkbox");
      labelEle.setAttribute("id", "container" + nameAddition);
      tdEle.appendChild(labelEle);
      var imgEle = document.createElement('img');
      imgEle.setAttribute("class", "img-fluid");
      imgEle.setAttribute("id", "img" + nameAddition);
      imgEle.setAttribute("src", surveyControl.image_paths[(i * 4) + j]);
      labelEle.appendChild(imgEle);
      var inputEle = document.createElement('input');
      inputEle.setAttribute("type", "checkbox");
      inputEle.setAttribute("id", "check" + nameAddition);
      inputEle.setAttribute("value", "");
      labelEle.appendChild(inputEle);
      var iconEle = document.createElement("i");
      iconEle.setAttribute("class", "fa fa-check");
      iconEle.setAttribute("id", "sel" + nameAddition);
      iconEle.hidden = true;
      labelEle.appendChild(iconEle);
    }
  }

  //Remove the placeholder spiner & replace with the table
  var questionSpace = document.getElementById('question_space');
  while(questionSpace.hasChildNodes()) {
    questionSpace.removeChild(questionSpace.firstChild);
  }
  questionSpace.appendChild(tableContainer);

  //Update the view based on check input
  $(".image-checkbox").on("click", function (e) {
    e.preventDefault();
    var $checkbox = $(this).find('input[type="checkbox"]');
    $checkbox.prop("checked",!$checkbox.prop("checked"));

    //Get the container ID (label element) of the checkbox "container_#"
    var clicked = $(this).attr('id');
    //Parse out the number of the element (array index in surveyControl.image_paths)
    var num = clicked.substring(clicked.indexOf("_") + 1);
    num = parseInt(num);
    //Get the checkbox and the icon elements
    var checkboxElement = document.getElementById('check_' + num);
    var iconElement = document.getElementById('sel_' + num);

    //The item was just checked
    if (checkboxElement.checked) {
      //4 items are already selected, so unselect the current and trigger alert
      if (selectionCount == 4) {
        $checkbox.prop("checked",!$checkbox.prop("checked"));
        alert("Select only 4 images. You may deselect other images if desired.");
        return;
      }
      selectionCount++;
      iconElement.hidden = false;
      choicesMade.push(num);
    }
    //The item was just unchecked
    else {
      selectionCount--;
      iconElement.hidden = true;
      for (var i = 0; i < choicesMade.length; i++) {
        if (choicesMade[i] == num)
          choicesMade.splice(i, 1);
      }
    }

    //Toggle the class to checked/unchecked as needed
    $(this).toggleClass('image-checkbox-checked');
    console.log(choicesMade);
    evalContinueButton();
  }); //END onclick function attach
} //END loadImages()


//executeUserSelection()
function executeUserSelection() {
  if (selectionCount != 4) {
    alert("Please select the 4 images you believe most likely to be misidentified by the model.");
    return;
  }
  else {
    choicesMade.push(seconds);
  }
  jQuery.ajax({
    type:     "POST",
    url:      '../../support_files/sql_interact.php',
    dataType: 'json',
    data:     {functionname: 'get_next_question', arguments: choicesMade},
    error:    function(a, b, c) {
                console.log("executeUserSelection() -> jQuery.ajax could not execute php file.");
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


//completeTimer()
function completeTimer() {
  timerDone = true;
  evalContinueButton()
} //END completeTimer()


//evalContinueButton()
function evalContinueButton() {
  if (timerDone && selectionCount == 4) {
    continueButton.setAttribute("style", "");
    continueButton.disabled = false;
  }
  else {
    continueButton.setAttribute("style", "display: none");
    continueButton.disabled = true;
  }
} //END evalContinueButton()
