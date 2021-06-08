<?php

include 'config.php';

error_reporting(0);

session_start();

if(isset($_POST['submit']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);

    if($password == $cpassword)
    {
        $sql = "SELECT * FROM User WHERE email = '$email";
        $result = mysqli_query($db,$sql);
        if(result)
        {
        	$sql = "INSERT INTO 'User' (username,email,password) VALUES(:username,:email,:password)";
        $stmt = $db->prepare($sql);
        $stmt -> bindParam(':username',$username);
        $stmt -> bindParam(':email',$email);
        $stmt -> bindParam(':password',$password);
        if($stmt->exec())
        	{
        		echo "<script>alert('Register Successfull!')</script>";
        		$username = "";
            	$email = "";
            	$_POST['password'] = "";
            	$_POST['cpassword'] = "";
            	header("Location: index.php");
        	}
        	else
        	{
        		echo "<script>alert('Oh no!')</script>";
        	}

        }
        else
        {
            echo "<script>alert('Woops! Something Wrong Went.')</script>";
        }
    }
    else 
    {
        echo "<script>alert('Password Not Matched')</script>";
    }
}

?>