<?php
include_once $_SERVER['DOCUMENT_ROOT']."/includes/config.php";
$ids = json_decode($_POST['ids']);

foreach($ids as $id){

    $run = mysql_fetch('DELETE FROM users WHERE id = :id',
    Array(':id'=>$id));
    
}
?>