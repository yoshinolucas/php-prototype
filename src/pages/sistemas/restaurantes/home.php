<?php
define('ROOT',$_SERVER['DOCUMENT_ROOT']);
include_once ROOT."/includes/config.php";
include_once ROOT."/includes/header.php";
include_once ROOT."/includes/top.php";
include_once ROOT."/includes/sidebar.php";
protege();
?>

<section class="restaurant">
    <div class="panel" id="panel">
        <div class="panel-header">
            <?php include_once ROOT."/includes/restaurant-nav.php"; ?>
        </div>
    </div>
</section>

<?php 
include_once ROOT."/includes/footer.php";
?>