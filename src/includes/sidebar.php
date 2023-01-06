<?php
include_once $_SERVER['DOCUMENT_ROOT']."/includes/config.php";
?>

<div class="sidebar" id="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
    <h2 class="menu">
        Menu
    </h2>
    <nav>
        <ul>
            <li><a href="home.php"><i class="fa-solid fa-dashboard"></i>Dashboard</a></li>
            <li><a href="users.php"><i class="fa-solid fa-user"></i>Usuários</a></li>
            <li><a><i class="fa-solid fa-clipboard-list"></i>Relatórios</a></li>
            <li><a><i class="fa-solid fa-database"></i>Database</a></li>
        </ul>
    </nav>
</div>
<div class="sidebar-compact">
    <button class="openbtn" onclick="openSidebar()">&#9776;</button>
</div>


<script>
    const openSidebar = () => {
        document.getElementById("sidebar").style.width = "200px";
        document.getElementById("panel").style.marginLeft = "224px";
    };

    const closeSidebar = () => {
        document.getElementById("sidebar").style.width = "0";
        document.getElementById("panel").style.marginLeft = "69px";
    };
</script>
