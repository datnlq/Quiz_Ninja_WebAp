<?php 

$file = 'config.php' ;
$file = basename(realpath($_GET['file']));
include($file);

session_start();

error_reporting(0);

/*if (isset($_SESSION['username'])) 
{
    header("Location: quiz_view.php");
}*/
/*echo 'Your input'.$_GET['input'];*/
if (isset($_POST['submit'])) 
{
	$email = $_POST['email'];
	$password = md5($_POST['password']);

	$sql = "SELECT * FROM User WHERE email=:email AND password=:password";
	$pre = $db->prepare($sql);
	$pre->bindParam(':email',$email);
	$pre->bindParam(':password',$password);
	$pre->execute();
	$rs = $pre->fetch();
	echo $rs['username'] ;
	//echo "<script>alert('Woops! Email or Password is Wrong.')</script>";
	if($rs['username'])
	{
		//echo "<script>alert('Woops!')</script>";
		$_SESSION['username'] = $rs['username'];
		$name = $rs['username'];
		$_SESSION['email'] = $rs['email'];
		header("Location: ../view/quiz_view.php");
	} else {
		echo "<script>alert('Woops! Email or Password is Wrong.')</script>";
	}
}

?>
