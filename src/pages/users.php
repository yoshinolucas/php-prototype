<?php
include_once '../includes/config.php';
include_once '../includes/header.php'; 
include_once '../includes/top.php';
include_once '../includes/sidebar.php';
?>
<section>
    <div class="panel" id="panel">    
        <div class="panel-header">
            <h4 style="text-align:left">Usuários</h4>
            <button class="add">
                <a>+</a>
            </button>
        </div>      
        <table id="usersTable" class="pt-2 table table-hover table-bordered table-striped" align="center" border="0" cellpadding="2" cellspacing="1" width="99%">
            <thead style="background: #444; color:#fff;">
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Criado em</th>
                    <th>Atualizado em</th>
                </tr>
            </thead>
            <tbody>              
            </tbody>
        </table>   
    </div>
</section>
<?php 
include_once '../includes/footer.php';
?>
<script>
    $(document).ready( function () {
        var table = $('#usersTable').DataTable({       
            processing: true,
            serverSide: true,
            ajax: {
                url:'api/users.datatables.php',
                type: 'POST'
            },
            language: {
                url: '/locale/dataTable.json'
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'email' },
                { data: 'criado_em' },
                { data: 'atualizado_em' }
            ]
        });

    });


</script>