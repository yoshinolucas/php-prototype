<?php 
include_once '../includes/config.php';
include_once '../includes/header.php'; 
include_once '../includes/top.php';
include_once '../includes/sidebar.php';

protege();
?>

    <section>
        <div class="panel" id="panel">
            <div class="panel-header">
                <h4 class="panel-title">Registrar vendas | Passo(1/4) / Escolha o tipo de cliente</h4>
            </div>
            <div class="panel-body">
                <div class='lista'>
                    <nav>
                        <ul>
                            <li><a class="add">Cadastrados</a></li>
                            <li><a class="add">Não cadastrados</a></li>
                        </ul>
                    </nav>
                </div>          
            </div>
        </div>
    </section>

<?php 
include '../includes/footer.php';
?>

<script>
    $(document).ready(function () {
        $('.add').on('click', function(){
            if($('#id').val() != '') {
                location.href="/pages/vendas-cadastrados.php"
            }
        })
    });
</script>