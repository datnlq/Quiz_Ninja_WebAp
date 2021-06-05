<?php


include 'config.php';

session_start();

error_reporting(0);


$question_title = "Currently the lord of the Rain village. He has two friends in the same situation, one of whom is dead.  ";
$A = "Yahiko";
$B = "Uzumaki Nagato";
$C = "Konan";
$D = "Uchiha Madara";
$Answer = "Uzumaki Nagato";

$sql = "INSERT INTO Question (question_title,A,B,C,D,Answer) VALUES (:question_title,:A,:B,:C,:D,:Answer)";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':question_title',$question_title);
	$stmt->bindValue(':A',$A);
	$stmt->bindValue(':B',$B);
	$stmt->bindValue(':C',$C);
	$stmt->bindValue(':D',$D);
	$stmt->bindValue(':Answer',$Answer);
	$stmt->execute();
?>
