<?php
include_once $_SERVER['DOCUMENT_ROOT']."/includes/config.php";
protege();
?>

<div class="sidebar" id="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
    <h2 class="menu">
        Menu
    </h2>
    <nav>
        <ul>
            <li><a href="/pages/home.php"><i class="fa-solid fa-dashboard"></i>Dashboard</a></li>
            <li><a href="/pages/users.php"><i class="fa-solid fa-user"></i>Usuários</a></li>
            <li class="sub-menu"><a><i class="fa-solid fa-clipboard-list"></i>
            Relatórios<div style="float:right;margin:5px 5px 0 0" class='fa fa-caret-down right'></div></a>
                <ul>
                    <li><a>Transportadoras</a></li>
                    <li><a href="/pages/relatorios/relatorio_vendas.php">Vendas</a></li>
                    <li><a href="/pages/relatorios/relatorio_alteracoes.php">Alterações</a></li>
                </ul>
            </li>
            <li class="sub-menu"><a><i class="fa-solid fa-clipboard-list"></i>
            Estoques<div style="float:right;margin:5px 5px 0 0" class='fa fa-caret-down right'></div></a>
                <ul>
                    <li><a href="/pages/estoques/produtos.php">Produtos</a></li>
                    <li><a>Escritório</a></li>
                </ul>
            </li>
            <li><a><i class="fa-solid fa-database"></i>Database</a></li>
        </ul>
    </nav>
</div>
<div class="sidebar-compact">
    <button class="openbtn" onclick="openSidebar()">&#9776;</button>
</div>

<script>
    $('.sub-menu ul').hide();
    $(".sub-menu a").click(function () {
        $(this).parent(".sub-menu").children("ul").slideToggle("100");
        $(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
    });
 </script>

<script>
    const openSidebar = () => {
        document.getElementById("sidebar").style.width = "200px";
        document.getElementById("panel").style.marginLeft = "224px";
        document.getElementById("header").style.marginLeft = "199px";
    };

    const closeSidebar = () => {
        document.getElementById("sidebar").style.width = "0";
        document.getElementById("panel").style.marginLeft = "69px";
        document.getElementById("header").style.marginLeft = "44px";
    };
</script>
