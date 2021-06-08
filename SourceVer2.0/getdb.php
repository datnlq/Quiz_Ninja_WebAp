<?php
include 'config.php' ;



$sql = "SELECT COUNT(*) AS row FROM User WHERE email=:email AND password=:password";
	$pre = $db->prepare($sql);
	$pre->bindParam(':email',$email);
	$pre->bindParam(':password',$password);
	$pre->execute();
	$rs = $pre->fetch();

	$username = $rs['username'];
	$emaill = $rs['email'];

?>