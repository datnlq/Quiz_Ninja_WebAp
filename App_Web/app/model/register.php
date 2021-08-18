<?php

$file = 'config.php' ;
$file = basename(realpath($_GET['file']));
include($file);

session_start();

error_reporting(0);

if(isset($_POST['submit']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);

    if($password == $cpassword)
    {
    	//echo "<script>alert('Password Is Matched')</script>";
    	$sql = "SELECT COUNT(*) AS row FROM User WHERE email=:email";
    	$pre = $db->prepare($sql) ;
    	$pre->bindParam(':email',$email);
    	$pre->execute();
    	$rs = $pre->fetch();
    	//echo "<script>alert('Nahnah')</script>";
    	if(!($rs['row'] > 0))
    	{
    		//echo "<script>alert('Email not exsist')</script>";
    		$sql = "INSERT INTO User (username,email,password) VALUES (:username,:email,:password)";
    		$stmt = $db->prepare($sql);
    		//echo "<script>alert('Nahnah')</script>";
    		$stmt->bindValue(':username',$username);
    		$stmt->bindValue(':email',$email);
    		$stmt->bindValue(':password',$password);
    		$stmt->execute();
 
    		header("Location: login_view.php");
    	}
    	else
    	{
    		echo "<script>alert('Email already exsist')</script>";
    	}
    }
    else
    {
    	echo "<script>alert('Password Not Matched')</script>";
    }
}
?>

