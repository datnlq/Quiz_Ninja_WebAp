<?php

const PATH_TO_SQLITE_FILE = "/Database/data.db";


if(!is_file('Database/data.db'))
{
  file_put_contents('Database/data.db', null);
}

try 
{
  $db = new PDO("sqlite:Database/data.db");
  //echo "<script>alert('checkemaildone')</script>";
}   
catch (Exception $e) 
{
  echo "Unable to connection  ";
  echo $e->getMessage();
  exit;
}

try
{
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query = "CREATE TABLE IF NOT EXISTS User(user_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,username TEXT NOT NULL ,email TEXT NOT NULL , password TEXT NOT NULL)";
  
  $db->exec($query);

  $query = "CREATE TABLE IF NOT EXISTS Question(question_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question_title TEXT, A TEXT, B TEXT, C TEXT, D TEXT, Answer TEXT)";
  
  $db->exec($query);
}
catch (Exception $e)
{
  echo $e ->getMessage() ;
}

?>