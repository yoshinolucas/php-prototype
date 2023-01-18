<?php
include_once '../includes/config.php';
include_once '../includes/header.php'; 
include_once '../includes/top.php';
include_once '../includes/sidebar.php';

protege();

$atalhos = mysql_fetchAll('SELECT * FROM atalhos');
?>
<section>
    <div class="panel" id="panel">
        <div class="panel-header">
            <h4 class="panel-title">Dashboard</h4>
        </div>
        <div class="panel-body">
            <div id="carouselExampleControls"  data-ride="carousel" class="carousel slide">
                <?php foreach($atalhos as $atalho) { ?>
                    <div class="carousel-item">
                        <a href="<?php print $atalho['link'] ?>">
                            <?php print $atalho['img'] ?></a>
                    </div>
                <?php } ?>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </div>
</section>
<?php 
include '../includes/footer.php';
?>