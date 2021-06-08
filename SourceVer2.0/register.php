<?php

include 'config.php';

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
 
    		header("Location: index.php");
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


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="style.css">

	<title>Ninja Register</title>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Register</p>
			<div class="input-group">
				<input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>" required>
			</div>
			<div class="input-group">
				<input type="email" placeholder="Email" name="email" value="<?php echo $email; ?>" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="Password" name="password" value="<?php echo $_POST['password']; ?>" required>
            </div>
            <div class="input-group">
				<input type="password" placeholder="Confirm Password" name="cpassword" value="<?php echo $_POST['cpassword']; ?>" required>
			</div>
			<div class="input-group">
				<button name="submit" class="btn">Register</button>
			</div>
			<p class="login-register-text">   >>> Have an account ? <a href="index.php">Login Here</a> <<<</p>
		</form>
	</div>
</body>
</html>
