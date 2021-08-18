<?php 

include '../model/question.php';

session_start();

error_reporting(0);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="../css/question.css">

	<title>Ninja Konoha</title>
</head>
<body>
	<div class="container">
		<form action="" method="GET" class="ques-ans">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">Question Ninja</p>
			<div class="input-group-ques">
				<input type="question" placeholder="Question" name="question" value="<?php echo $question; ?>" required>
			</div>
			<div class="input-group">
				<input type="option" placeholder="Option1" name="option1" value="<?php echo $option1; ?>" required>
			</div>
			<div class="input-group">
				<input type="option" placeholder="Option2" name="option2" value="<?php echo $option2; ?>" required>
			</div>
			<div class="input-group">
				<input type="option" placeholder="Option3" name="option3" value="<?php echo $option3; ?>" required>
			</div>
			<div class="input-group">
				<input type="option" placeholder="Option4" name="option4" value="<?php echo $option4; ?>" required>
			</div>
			<div class="input-group">
				<input type="anwser" placeholder="Answer" name="answer" value="<?php echo $answer; ?>" required>
			</div>


			<div class="input-group">
				<button name="submit" class="btn">Add</button>
			</div>
			<p class="login-register-text">>>>>>>>>>>>>>Get back quiz? <a href="quiz_view.php">Quiz</a><<<<<<<<<<<</p>

		</form>
	</div>
</body>
</html>
