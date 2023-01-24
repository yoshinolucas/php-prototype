<?php
include_once $_SERVER['DOCUMENT_ROOT']."/includes/config.php";
protege();
?>

<header class="header" id="header">
    <a class="logo">Nome da Empresa</a>
    
    <div class="options-top">
        <div class="theme-switch-wrapper">
            <label class="theme-switch" for="checkbox">
                <input type="checkbox" id="checkbox" />
                <div class="slider round"></div>
            </label>
        </div>
        <button class="perfil"><i class="fa fa-user"></i></button>
        <button class="options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
    </div>
    
</header>


<script>
    const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);
    
        if (currentTheme === 'dark') {
            toggleSwitch.checked = true;
        }
    }

    function switchTheme(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        }
        else {        document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }    
    }

    toggleSwitch.addEventListener('change', switchTheme, false);
</script>


