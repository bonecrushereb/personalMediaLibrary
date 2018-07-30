<?php 
include("inc/functions.php");

$pageTitle = "Full Catalog";
$section = null;
$itemPerPage = 8;

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

if (isset($_GET['pg'])) {
    $currentPage = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_NUMBER_INT);
}

if (empty($currentPage)) {
    $currentPage = 1;
}

$totalItems = getCatalogCount($section);
$totalPages = ceil($totalItems / $itemsPerPage);

$limitResults = '';
if (!empty($section)) {
    $limitResults = 'cat=' .  $section . '&';
}

if ($currentPage > $totalPages) {
    header('location:catalog.php?' . $limitResults . 'pg=' . $totalPages);
} elseif($currentPage < 1) {
    header('location:catalog.php?' . $limitResults . 'pg=1');
}

$offset = ($currentPage - 1) * $itemsPerPage;

if (empty($section)) {
    $catalog = fullCatalogArray();
} else {
    $catalog = categoryCatalogArray($section);
}

include("inc/header.php"); ?>

<div class="section catalog page">
    
    <div class="wrapper">
        
        <h1><?php 
        if ($section != null) {
            echo "<a href='catalog.php'>Full Catalog</a> &gt; ";
        }
        echo $pageTitle; ?></h1>
        
        <ul class="items">
            <?php
            foreach ($catalog as $item) {
                echo getItemHtml($item);
            }
            ?>
        </ul>
        
    </div>
</div>

<?php include("inc/footer.php"); ?>