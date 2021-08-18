<?php


include '../model/config.php';

session_start();

error_reporting(0);


function randomGen($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}


$question = "";
$questions = "";
for($i = 0; $i < 20; $i++)
{
    do
    {
    $a = rand(0,23);

    $sql = "SELECT question_id,question_title,A,B,C,D,Answer FROM `Question` WHERE question_id =:question_id";
    $pre = $db->prepare($sql) ;
        $pre->bindParam(':question_id',$a);
        $pre->execute();
        $rs = $pre->fetch();
        $question_id = $rs['question_id'];
        $question_title = $rs['question_title'];

        $A = $rs['A'];
        
        $B = $rs['B'];
        
        $C = $rs['C'];
        
        $D = $rs['D'];
        
        $Answer = $rs['Answer'];
    }while($question_title == "" || $A == "" || $B == "" || $C == "" || $D == "" || $Answer == "");
        
        /*echo $question_id ;
        echo $A ;
        echo $B ;
        echo $C ;
        echo $D ;
        echo $Answer ."</br>";*/
        
        $question = $question.$question_title."|".$A."|".$B."|".$C."|".$D."|".$Answer."|" ;

}
?>
