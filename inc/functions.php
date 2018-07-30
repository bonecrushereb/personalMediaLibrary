<?php
function getCatalogCount($category = null) {
    $category = strtolower($category);
    include("connection.php");

    try {
      $sql = "SELECT COUNT(media_id) FROM Media";
      if(!empty($category)) {
        $result = $db -> prepare($sql . "WHERE LOWER(category) = ?");
        $result -> bindParam(1, $category, PDO::PARAM_STR);
      } else {
        $result = $db -> prepare($sql);
      }
      $result -> execute();
    } catch (Exception $e) {
      echo $e;
    }

    $count = $result -> fetchColumn(0);
    return $count;
}
function fullCatalogArray() {
    include("connection.php");

    try {
       $results = $db->query("SELECT media_id, title, category,img 
                              FROM Media
                              ORDER BY
                                REPLACE(
                                   REPLACE(
                                     REPLACE(title,'The ',''),
                                        'An ', ''
                                       ),
                                       'A ', ''
                                     )");
    } catch (Exception $e) {
       echo $e;
       exit;
    }
    
    $catalog = $results->fetchAll();
    return $catalog;
}

function singleItemArray($id) {
    include("connection.php");

    try {
       $results = $db->prepare("SELECT title, category, img, format, year , genre, publisher, isbn
                              FROM Media
                              JOIN Genres ON Media.genre_id = Genres.genre_id
                              LEFT OUTER JOIN Books ON Media.media_id = Books.media_id
                              WHERE Media.media_id = ?");
       $results->bindParam(1, $id, PDO::PARAM_INT);
       $results->execute();
    } catch (Exception $e) {
       echo $e;
       exit;
    }
    
    $item = $results->fetch();
    if(empty($item)) return $item;
    try {
       $results = $db->prepare("SELECT fullname, role
                              FROM Media_People
                              JOIN People ON Media_People.people_id = People.people_id
                              WHERE Media_People.media_id = ?");
       $results->bindParam(1, $id, PDO::PARAM_INT);
       $results->execute();
    } catch (Exception $e) {
       echo $e;
       exit;
    }
    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
        $item[$row['role']][] = $row['fullname'];
    }
    return $item;
}

function randomCatalogArray() {
    include("connection.php");

    try {
       $results = $db->query("SELECT media_id, title, category,img 
                              FROM Media
                              ORDER BY RAND()
                              LIMIT 4");
    } catch (Exception $e) {
       echo $e;
       exit;
    }
    
    $catalog = $results->fetchAll();
    return $catalog;
}

function categoryCatalogArray($category) {
    include("connection.php");
    $category = strtolower($category);

    try {
       $results = $db->prepare("SELECT media_id, title, category,img 
                                FROM Media
                                WHERE LOWER(category) = ?
                                ORDER BY
                                REPLACE(
                                   REPLACE(
                                     REPLACE(title,'The ',''),
                                        'An ', ''
                                       ),
                                       'A ', ''
                                     )"
                                   );
       $results -> bindParam(1, $category, PDO::PARAM_STR);
       $results -> execute();
    } catch (Exception $e) {
       echo $e;
       exit;
    }
    
    $catalog = $results->fetchAll();
    return $catalog;
}

function getItemHtml($item) {
    $output = "<li><a href='details.php?id="
        . $item['media_id'] . "'><img src='" 
        . $item["img"] . "' alt='" 
        . $item["title"] . "' />" 
        . "<p>View Details</p>"
        . "</a></li>";
    return $output;
}

function arrayCategory($catalog,$category) {
    $output = array();
    
    foreach ($catalog as $id => $item) {
        if ($category == null OR strtolower($category) == strtolower($item["category"])) {
            $sort = $item["title"];
            $sort = ltrim($sort,"The ");
            $sort = ltrim($sort,"A ");
            $sort = ltrim($sort,"An ");
            $output[$id] = $sort;            
        }
    }
    
    asort($output);
    return array_keys($output);
}