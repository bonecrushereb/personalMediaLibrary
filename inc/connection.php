<?php

  try{  
    $dsn = "mysql:host=localhost;dbname=database;port=8889"; $username = "root"; $password = "root"; 
    $db = new PDO($dsn, $username, $password); 
    var_dump($db); 
  } catch(Exception $e) {
    echo $e;
    exit;
  }

  echo 'connected to database';
