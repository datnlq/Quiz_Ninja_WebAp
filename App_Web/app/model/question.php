<?php 

include 'config.php' ;

session_start();

error_reporting(0);

if (isset($_GET['submit'])) 
{
	$question = $_GET['question'];
	$option1 = $_GET['option1'];
	$option2 = $_GET['option2'];
	$option3 = $_GET['option3'];
	$option4 = $_GET['option4'];
	$answer = $_GET['answer'];
	$blacklist_symbol = array(" ","!","@","#","|","%","^","&","*","~","\\","/","<",">");
	$question = str_replace($blacklist_symbol,"",$question);
	$option1 = str_replace($blacklist_symbol,"",$option1);
	$option2 = str_replace($blacklist_symbol,"",$option2);
	$option3 = str_replace($blacklist_symbol,"",$option3);
	$option4 = str_replace($blacklist_symbol,"",$option4);
	$answer = str_replace($blacklist_symbol,"",$answer);


	$blacklist_command = array("ls","cat","less","tail","more","whoami","pwd","echo");
	$question = str_replace($blacklist_command,"",$question);
	$option1 = str_replace($blacklist_command,"",$option1);
	$option2 = str_replace($blacklist_command,"",$option2);
	$option3 = str_replace($blacklist_command,"",$option3);
	$option4 = str_replace($blacklist_command,"",$option4);
	$answer = str_replace($blacklist_command,"",$answer);

	if($answer != $option1 &&$answer != $option2 &&$answer != $option3 &&$answer != $option4 )
	{
		echo "<script>alert('Wrong answer')</script>";
	}else
	{
		$sql = "INSERT INTO Question (question_title,A,B,C,D,Answer) VALUES (:question_title,:A,:B,:C,:D,:Answer)";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':question_title',$question);
		$stmt->bindValue(':A',$option1);
		$stmt->bindValue(':B',$option2);
		$stmt->bindValue(':C',$option3);
		$stmt->bindValue(':D',$option4);
		$stmt->bindValue(':Answer',$answer);
		try
		{
			$stmt->execute();
			echo "<script>alert('Add Question Completed')</script>"; 
			echo $question ;
		}
		catch (Exception $e) 
		{
			echo $e ->getMessage() ;
		}
	}

	$question = $option1 = $option2 = $option3 = $option4 = $answer = null ;
	
}

?>
