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
            <li><a href="/pages/vendas.php"><i class="fa-solid fa-shop"></i>Vendas</a></li>
            <li class="sub-menu"><a><i class="fa-solid fa-user"></i>
            Pessoas<div style="float:right;margin:5px 5px 0 0" class='fa fa-caret-down right'></div></a>
                <ul>
                    <li><a href="/pages/pessoas/users.php">Usuários</a></li>
                    <li><a href="/pages/pessoas/clientes.php">Clientes</a></li>
                </ul>
            </li>
            <li class="sub-menu"><a><i class="fa-solid fa-clipboard-list"></i>
            Relatórios<div style="float:right;margin:5px 5px 0 0" class='fa fa-caret-down right'></div></a>
                <ul>
                    <li><a>Transportadoras</a></li>
                    <li><a href="/pages/relatorios/relatorio_vendas.php">Vendas</a></li>
                    <li><a href="/pages/relatorios/relatorio_alteracoes.php">Alterações</a></li>
                </ul>
            </li>
            <li class="sub-menu"><a><i class="fa-solid fa-dolly"></i>
            Estoques<div style="float:right;margin:5px 5px 0 0" class='fa fa-caret-down right'></div></a>
                <ul>
                    <li><a href="/pages/estoques/produtos.php">Produtos</a></li>
                    <li><a>Escritório</a></li>
                </ul>
            </li>
            <li class="sub-menu"><a><i class="fa-solid fa-money-check-dollar"></i>
            Mensalidades<div style="float:right;margin:5px 5px 0 0" class='fa fa-caret-down right'></div></a>
                <ul>
                    <li><a href="/pages/mensalidade/mensalidade-cliente.php">Clientes</a></li>
                    <li><a>Escritório</a></li>
                </ul>
            </li>
            <li><a><i class="fa-solid fa-database"></i>Database</a></li>
        </ul>
    </nav>
    <div class="logout">
        <a href="/index.php?action=logout" style="color:#fff;font-size:16px">Sair
        <i class="fa-sharp fa-solid fa-arrow-right-from-bracket"></i></a>
    </div>
</div>
<div class="sidebar-compact">
    <button class="openbtn" onclick="openSidebar()">&#9776;</button>
</div>

<script>
    function sleep (time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }
    $('.sub-menu ul').hide();
    $(".sub-menu a").click(function () {
        $(this).parent(".sub-menu").children("ul").slideToggle("100");
        $(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
    });
    const openSidebar = () => {
        document.getElementById("sidebar").style.width = "200px";
        document.getElementById("panel").style.marginLeft = "224px";
        document.getElementById("header").style.marginLeft = "199px";
        sleep
        sleep(300).then(()=>{
            table.draw();
        }) 
    };

    const closeSidebar = () => {
        document.getElementById("sidebar").style.width = "0";
        document.getElementById("panel").style.marginLeft = "70px";
        document.getElementById("header").style.marginLeft = "44px";
        sleep(300).then(()=>{
            table.draw();
        }) 
    };
</script>
