<?php 
include("inc/functions.php");

$pageTitle = "Personal Media Library";
$section = null;

include("inc/header.php"); ?>
		<div class="section catalog random">

			<div class="wrapper">

				<h2>May we suggest something?</h2>

        <ul class="items">
            <?php
            $random = randomCatalogArray($catalog,4);
            foreach ($random as $item) {
                echo getItemHtml($item);
            }
            ?>							
				</ul>

			</div>

		</div>

<?php include("inc/footer.php"); ?>