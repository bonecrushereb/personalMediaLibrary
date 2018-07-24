<?php

  try{  
    $dsn = "mysql:host=localhost;dbname=database;port=8889"; $username = "root"; $password = "root"; 
    $db = new PDO($dsn, $username, $password);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(Exception $e) {
    echo $e->getMessage();
    exit;
  }

  try {
    $results = $db -> query('SELECT title, category, img FROM Media');
  } catch (Exception $e) {
    echo 'unable to recieve results';
    exit;
  }

  $catalog = $results -> fetchAll();