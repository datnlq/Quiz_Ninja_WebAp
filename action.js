let questions = [
    {
    numb: 1,
    question: "What does HTML stand for?",
    answer: "Hyper Text Markup Language",
    options: [
      "Hyper Text Preprocessor",
      "Hyper Text Markup Language",
      "Hyper Text Multiple Language",
      "Hyper Tool Multi Language"
    ]
  },
    {
    numb: 2,
    question: "What does CSS stand for?",
    answer: "Cascading Style Sheet",
    options: [
      "Common Style Sheet",
      "Colorful Style Sheet",
      "Computer Style Sheet",
      "Cascading Style Sheet"
    ]
  },
    {
    numb: 3,
    question: "What does PHP stand for?",
    answer: "Hypertext Preprocessor",
    options: [
      "Hypertext Preprocessor",
      "Hypertext Programming",
      "Hypertext Preprogramming",
      "Hometext Preprocessor"
    ]
  },
    {
    numb: 4,
    question: "What does SQL stand for?",
    answer: "Structured Query Language",
    options: [
      "Stylish Question Language",
      "Stylesheet Query Language",
      "Statement Question Language",
      "Structured Query Language"
    ]
  },
    {
    numb: 5,
    question: "What does XML stand for?",
    answer: "eXtensible Markup Language",
    options: [
      "eXtensible Markup Language",
      "eXecutable Multiple Language",
      "eXTra Multi-Program Language",
      "eXamine Multiple Language"
    ]
  },
  // you can uncomment the below codes and make duplicate as more as you want to add question
  // but remember you need to give the numb value serialize like 1,2,3,5,6,7,8,9.....

  //   {
  //   numb: 6,
  //   question: "Your Question is Here",
  //   answer: "Correct answer of the question is here",
  //   options: [
  //     "Option 1",
  //     "option 2",
  //     "option 3",
  //     "option 4"
  //   ]
  // },
];

//selecting all required elements
const start = document.querySelector(".start button");
const quiz_box = document.querySelector(".quiz_box");
const result_box = document.querySelector(".result_box");
const option_list = document.querySelector(".option_list");
const quit_quiz = document.querySelector(".quit");
const restart_quiz = document.querySelector(".restart");


quit_quiz.onclick=()=>{
  window.location.reload();
}

restart_quiz.onclick=()=>{
  result_box.classList.remove("activeResult");
  activeQuiz();
}
let que_count = 0;
let que_numb = 1;
let userScore = 0;

function activeQuiz()
{
    quiz_box.classList.add("activeQuiz");
    showQuetions(0);
}

start.onclick =()=>
{
    activeQuiz();
}


function showQuetions(index){
    const que_txt = document.querySelector(".que_txt");
    

    //creating a new span and div tag for question and option and passing the value using array index
    let que_title = '<span>'+ questions[index].numb + ". " + questions[index].question +'</span>';
    let option_tag = '<div class="option"><span>'+ questions[index].options[0] +'</span></div>'
                        + '<div class="option"><span>'+ questions[index].options[1] +'</span></div>'
                        + '<div class="option"><span>'+ questions[index].options[2] +'</span></div>'
                        + '<div class="option"><span>'+ questions[index].options[3] +'</span></div>';
    que_txt.innerHTML = que_title;
    option_list.innerHTML = option_tag; 

    const option = option_list.querySelectorAll(".option");

    for(i=0; i < option.length; i++){
        option[i].setAttribute("onclick", "optionSelected(this)");

    }
}


let tickIconTag = '<div class="icon tick"><i class="fas fa-check"></i></div>';
let crossIconTag = '<div class="icon cross"><i class="fas fa-times"></i></div>';


function optionSelected(answer)
{
  let userAns = answer.textContent ;
  let correctAnswer = questions[que_count].answer ;
  const allOptions = option_list.children.length; 

  if(userAns == correctAnswer)
  {
    userScore++;
    answer.classList.add("correct");
    console.log(userAns)  ;
    console.log(correctAnswer)  ;
    answer.insertAdjacentHTML("beforeend", tickIconTag);
  }
  else
  {
    answer.classList.add("incorrect");
    console.log(userAns)  ;
    console.log(correctAnswer)  ;
    answer.insertAdjacentHTML("beforeend", crossIconTag);
  }
  for(i=0; i < allOptions; i++)
  {
    option_list.children[i].classList.add("disabled"); //once user select an option then disabled all options
  }  
    setTimeout(nextQues, 800);
}

function queCounter(index)
{
    //creating a new span tag and passing the question number and total question
    let totalQueCounTag = '<span><p>'+ index +'</p> of <p>'+ questions.length +'</p> Questions</span>';
}

function nextQues()
{
  if(que_count < questions.length - 1)
  { 
    que_count++;
    que_numb++; 
    showQuetions(que_count); 
    queCounter(que_numb);
  }
  else
  {
    showResult();
  }
    
}

function showResult(){
    quiz_box.classList.remove("activeQuiz");
    result_box.classList.add("activeResult");
    const scoreText = result_box.querySelector(".score_text");
    if (userScore > 15){ // if user scored more than 3
        let scoreTag = '<span>and congrats! You a Chunin 🎉, You got <p>'+ userScore +'</p> out of <p>'+ questions.length +'</p></span>';
        scoreText.innerHTML = scoreTag; 
    }
    else if(userScore > 10){ 
        let scoreTag = '<span>and nice ! You a Genin 😎, You got <p>'+ userScore +'</p> out of <p>'+ questions.length +'</p></span>';
        scoreText.innerHTML = scoreTag;
    }
    else{ // if user scored less than 1
        let scoreTag = '<span>You failed 😐, You got only <p>'+ userScore +'</p> out of <p>'+ questions.length +'</p></span>';
        scoreText.innerHTML = scoreTag;
    }
}