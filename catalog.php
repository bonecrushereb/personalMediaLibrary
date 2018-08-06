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

$totalItems = getCatalogCount($section, $search);
$totalPages = ceil($totalItems / $itemsPerPage);

$limitResults = '';
if (!empty($section)) {
    $limitResults = "cat=" .  $section . "&";
}

if ($currentPage > $totalPages) {
    header('location:catalog.php?' . $limitResults . 'pg=' . $totalPages);
} elseif($currentPage < 1) {
    header('location:catalog.php?' . $limitResults . 'pg=1');
}

$offset = ($currentPage - 1) * $itemsPerPage;

if (!empty($search)) {
  $catalog = searchCatalogArray($search,$itemsPerPage,$offset);
} else if (empty($section)) {
  $catalog = fullCatalogArray($itemsPerPage,$offset);
} else {
  $catalog = categoryCatalogArray($section,$itemsPerPage,$offset);
}

   $pagination = "<div class=\"pagination\">";
   $pagination .= "Pages: "; 
   for ($i=1; $i <= $totalPages; $i++) {
    if ($i == $currentPage) {
        $pagination .= " <span>$i</span>";
    } else {
        $pagination .= " <a href='catalog.php?";
        if (!empty($section)) {
            $pagination .= "cat=" . $section . '&';   
        }
        $pagination .= "pg=$i'>$i</a>";
        }
    }
   $pagination .= "</div>";

include("inc/header.php"); ?>

<div class="section catalog page">
    
    <div class="wrapper">
        
        <h1><?php 
        if ($section != null) {
            echo "<a href='catalog.php'>Full Catalog</a> &gt; ";
        }
        echo $pageTitle; ?></h1>
        <?php echo $pagination; ?>
        <ul class="items">
            <?php
            foreach ($catalog as $item) {
                echo getItemHtml($item);
            }
            ?>
        </ul>
        <?php echo $pagination; ?>
    </div>
</div>

<?php include("inc/footer.php"); ?>