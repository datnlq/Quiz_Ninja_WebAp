<?php


include 'config.php';

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
    $a = rand(1,20);

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
        
        /*echo $question_id ;
        echo $question_title ;
        echo $A ;
        echo $B ;
        echo $C ;
        echo $D ;
        echo $Answer ."</br>";*/
        $question = $question.$question_title."|".$A."|".$B."|".$C."|".$D."|".$Answer."|" ;
}

    
        ####---------------------------

        $sql = "SELECT COUNT(*) AS row FROM `Top` ";
        $pre = $db->prepare($sql) ;
        $pre->execute();
        $rs = $pre->fetch();
        $index = $rs['row'] ;
        for($c = $index; $c >= ($index-5) ; $c--)
        {
            $sql = "SELECT COUNT(*) AS row FROM `Top` WHERE id=:id ";
            $pre = $db->prepare($sql) ;
            $pre->bindParam(':id',$c);
            $pre->execute();

            $username = $rs['username'] ;
            $email = $rs['email'] ;
            $Score = $rs['Score'] ;

            $scorelist = $scorelist.$username."|".$email."|".$Score."|" ;
        }
        echo $scorelist
?>
