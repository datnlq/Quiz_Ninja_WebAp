<?php
include 'ques.php';

session_start();

error_reporting(0);

echo "<script>alert('$question']')</script>" ;
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Ninja Quizz</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
		<link rel="stylesheet" type="text/css" href="quizz.css"/>

</head>
<body>
	<div class="start">
			<button name="startt" class="button">Start</button>
	</div>


	<div class="quiz_box">
		<header>
			<div class="title">Ninja Quizz</div>
		</header>
		<section>
			<div class="que_txt">
				<!-- <span>How many type of rasengan?</span> -->
			</div>
			<div class="option_list">
				<div class ="option">
				 	<!-- <span>Hyper texttttttttttttt</span> -->
				 	<div class="icon tick"><i class="fas fa-check"></i></div>
				</div>
				<div class ="option">
				 	<!-- <span>Hyper texttttttttttttt</span> -->
				 	<div class="icon tick"><i class="fas fa-check"></i></div>
				</div>
				<div class ="option">
				 	<!-- <span>Hyper texttttttttttttt</span> -->
				 	<div class="icon tick"><i class="fas fa-check"></i></div>
				</div>
				<div class ="option">
				 	<!-- <span>Hyper texttttttttttttt</span> -->
				 	<div class="icon tick"><i class="fas fa-check"></i></div>
				</div>
			</div>
		</section>
	</div>

	<div class="result_box">
        <div class="icon">
            <img src="akat.png" alt="Akatsuki"height="71" width="100">
        </div>
        <div class="complete_text">You've completed the Quiz!</div>
        <div class="score_text">
            <!-- Here I've inserted Score Result from JavaScript -->
        </div>
        <div class="buttons">
            <button class="restart">Again?</button>
            <button class="quit">Go home zZz</button>
        </div>
    </div>
    
    <script type="text/javascript">
    var question = "<?php echo"$question"?>" 
    console.log(question);
    var ques = question.split("|"); 
    console.log(ques[6]);
    console.log(ques[7]);
    console.log(ques[8]);
    console.log(ques[9]);
    console.log(ques[10]);
    console.log(ques[11]);



    var questions = [
    {

    question: ques[0],
    optionA: ques[1],
    optionB: ques[2],
    optionC: ques[3],
    optionD: ques[4],
    answer:  ques[5]
  	},
  	{

    question: ques[6],
    optionA: ques[7],
    optionB: ques[8],
    optionC: ques[9],
    optionD: ques[10],
    answer:  ques[11]
  	},
  	{

    question: ques[12],
    optionA: ques[13],
    optionB: ques[14],
    optionC: ques[15],
    optionD: ques[16],
    answer:  ques[17]
  	},
  	{

    question: ques[18],
    optionA: ques[19],
    optionB: ques[20],
    optionC: ques[21],
    optionD: ques[22],
    answer:  ques[23]
  	},
  	{

    question: ques[24],
    optionA: ques[25],
    optionB: ques[26],
    optionC: ques[27],
    optionD: ques[28],
    answer:  ques[29]
  	},
  	{

    question: ques[30],
    optionA: ques[31],
    optionB: ques[32],
    optionC: ques[33],
    optionD: ques[34],
    answer:  ques[35]
  	},
  	{

    question: ques[36],
    optionA: ques[37],
    optionB: ques[38],
    optionC: ques[39],
    optionD: ques[40],
    answer:  ques[41]
  	},
  	{

    question: ques[42],
    optionA: ques[43],
    optionB: ques[44],
    optionC: ques[45],
    optionD: ques[46],
    answer:  ques[47]
  	},
  	{

    question: ques[48],
    optionA: ques[49],
    optionB: ques[50],
    optionC: ques[51],
    optionD: ques[52],
    answer:  ques[53]
  	},
  	{

    question: ques[54],
    optionA: ques[55],
    optionB: ques[56],
    optionC: ques[57],
    optionD: ques[58],
    answer:  ques[59]
  	},
  	{
  	question: ques[60],
    optionA: ques[61],
    optionB: ques[62],
    optionC: ques[63],
    optionD: ques[64],
    answer:  ques[65]
  	},
  	{

    question: ques[66],
    optionA: ques[67],
    optionB: ques[68],
    optionC: ques[69],
    optionD: ques[70],
    answer:  ques[71]
  	},
  	{

    question: ques[72],
    optionA: ques[73],
    optionB: ques[74],
    optionC: ques[75],
    optionD: ques[76],
    answer:  ques[77]
  	},
  	{

    question: ques[78],
    optionA: ques[79],
    optionB: ques[80],
    optionC: ques[81],
    optionD: ques[82],
    answer:  ques[83]
  	},
  	{

    question: ques[84],
    optionA: ques[85],
    optionB: ques[86],
    optionC: ques[87],
    optionD: ques[88],
    answer:  ques[89]
  	},
  	{

    question: ques[90],
    optionA: ques[91],
    optionB: ques[92],
    optionC: ques[93],
    optionD: ques[94],
    answer:  ques[95]
  	},
  	{

    question: ques[96],
    optionA: ques[97],
    optionB: ques[98],
    optionC: ques[99],
    optionD: ques[100],
    answer:  ques[101]
  	},
  	{

    question: ques[102],
    optionA: ques[103],
    optionB: ques[104],
    optionC: ques[105],
    optionD: ques[106],
    answer:  ques[107]
  	},
  	{

    question: ques[108],
    optionA: ques[109],
    optionB: ques[110],
    optionC: ques[111],
    optionD: ques[112],
    answer:  ques[113]
  	},
  	{

    question: ques[114],
    optionA: ques[115],
    optionB: ques[116],
    optionC: ques[117],
    optionD: ques[118],
    answer:  ques[119]
  	}
	];




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
	    let que_title = '<span>' +  questions[index].question +'</span>';
	    let option_tag = '<div class="option"><span>'+ questions[index].optionA +'</span></div>'
	                        + '<div class="option"><span>'+ questions[index].optionB +'</span></div>'
	                        + '<div class="option"><span>'+ questions[index].optionC +'</span></div>'
	                        + '<div class="option"><span>'+ questions[index].optionD +'</span></div>';
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
	  let correctAnswer = questions[que_count].answer;;
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
	        let scoreTag = '<span>Congrats! You a Chunin 🎉, You got <p>'+ userScore +'</p>/<p>'+ 20 +'</p></span>';
	        scoreText.innerHTML = scoreTag; 
	    }
	    else if(userScore > 5){ 
	        let scoreTag = '<span>Nice ! You a Genin 😎, You got <p>'+ userScore +'</p>/<p>'+ 20 +'</p></span>';
	        scoreText.innerHTML = scoreTag;
	    }
	    else{ // if user scored less than 1
	        let scoreTag = '<span>You failed 😐, You got only <p>'+ userScore +'</p>/<p>'+ 20 +'</p></span>';
	        scoreText.innerHTML = scoreTag;
	    }
	}
    </script>
</body>
</html>
