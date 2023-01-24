<?php
define('ROOT',$_SERVER['DOCUMENT_ROOT']);
include_once ROOT."/includes/config.php";
include_once ROOT."/includes/header.php";
include_once ROOT."/includes/top.php";
include_once ROOT."/includes/sidebar.php";
?>
<section>
    <div class="panel" id="panel">
        <div class="panel-header">
            <h5 class="panel-title">Mensalidade dos clientes</h5>
        </div>
        <div class="panel-body">
            <table class="table02" id='mensalidades' width='99%'>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Plano</th>
                        <th>Data vencimento</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</section>

<?php 
include_once ROOT."/includes/footer.php";
?>

<script>
    var table = $('#mensalidades').DataTable({
        responsive:{
            details: false
        },
        processing: true,
        serverSide: true,
        info: false,
        lengthChange:false,
        ajax: {
            url: 'api/mensalidade-cliente.datatables.php',
            type: 'POST',
            data: function(d){

            }
        },
        columns: {
            
        }
    });
</script>