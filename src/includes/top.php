<?php
include_once $_SERVER['DOCUMENT_ROOT']."/includes/config.php";
protege();
?>

<header class="header" id="header">
    <a class="logo">Nome da Empresa</a>
    <div>
        <button class="perfil"><i class="fa fa-user"></i></button>
        <button class="options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
        <a href="/index.php?action=logout" style="color:#fff;margin-right:32px">
        <i class="fa-sharp fa-solid fa-arrow-right-from-bracket"></i></a>
    </div>
    
</header>


