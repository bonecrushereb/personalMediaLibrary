<?php 
include("inc/functions.php");

$pageTitle = "Full Catalog";
$section = null;
$search = null;
$itemsPerPage = 8;

if (isset($_GET["cat"])) {
    if ($_GET["cat"] == "books") {
        $pageTitle = "Books";
        $section = "books";
    } else if ($_GET["cat"] == "movies") {
        $pageTitle = "Movies";
        $section = "movies";
    } else if ($_GET["cat"] == "music") {
        $pageTitle = "Music";
        $section = "music";
    }
}

if (isset($_GET["s"])) {
  $search = filter_input(INPUT_GET,"s",FILTER_SANITIZE_STRING);
}
if (isset($_GET["pg"])) {
  $currentPage = filter_input(INPUT_GET,"pg",FILTER_SANITIZE_NUMBER_INT);
}
if (empty($currentPage)) {
  $currentPage = 1;
}

$totalItems = getCatalogCount($section,$search);
$totalPages = 1;
$offset = 0;
if ($totalItems >0 ) {
  $totalPages = ceil($totalItems / $itemsPerPage);

  
  if ($currentPage > $totalPages) {
    header("location:catalog.php?" 
           . $limitResults 
           . "pg=".$totalPages);
  }
  if ($currentPage < 1) {
    header("location:catalog.php?"
           . $limit_results
           . "pg=1");
  }
  
  $offset = ($currentPage - 1) * $itemsPerPage;

  $pagination = "<div class=\"pagination\">";
  $pagination .= "Pages: ";  
  for ($i = 1;$i <= $totalPages;$i++) {
    if ($i == $currentPage) {
      $pagination .= " <span>$i</span>";
    } else {
      $pagination .= " <a href='catalog.php?";
      if (!empty($search)) {
        $pagination .= "s=" . urlencode(htmlspecialchars($search)) . "&";
      } else if (!empty($section)) {
        $pagination .= "cat=".$section."&";
      }
      $pagination .= "pg=$i'>$i</a>";
    }
  }
  $pagination .= "</div>";
}

if (!empty($search)) {
  $catalog = searchCatalogArray($search,$itemsPerPage,$offset);
} else if (empty($section)) {
  $catalog = fullCatalogArray($itemsPerPage,$offset);
} else {
  $catalog = categoryCatalogArray($section,$itemsPerPage,$offset);
}

include("inc/header.php"); ?>

<div class="section catalog page">
    
    <div class="wrapper">
        
        <h1><?php 
       if ($search != null) {
          echo "Search Results for \"".htmlspecialchars($search)."\"";
        } else {
          if ($section != null) {
            echo "<a href='catalog.php'>Full Catalog</a> &gt; ";
          }
          echo $pageTitle;
        } 
        ?></h1>
        <?php 
        if($totalItems < 1) {
            echo "<p>No items were found matching that search term.</p>";
            echo "<p>Search Again or" . "<a href=\"catalog.php\"> Browse the Full Catalog</a></p>";
        } else {
        echo $pagination; 
        ?>
        <ul class="items">
            <?php
            foreach ($catalog as $item) {
                echo getItemHtml($item);
            }
            ?>
        </ul>
        <?php 
            echo $pagination; 
        } ?>
    </div>
</div>

<?php include("inc/footer.php"); ?>