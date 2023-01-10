<?php
include_once '../includes/config.php';
include_once '../includes/header.php'; 
include_once '../includes/top.php';
include_once '../includes/sidebar.php';
?>
<section>
    <div class="panel" id="panel">    
        <div class="panel-header">
            <h4 class="panel-title">Usuários</h4>
            <a class="add" href="/pages/useredit.php?id=0"><i class="fa fa-add"></i></a>
        </div> 
        <div class="panel-body">
            <div class="panel-actions">
                <button class="remove" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-trash"></i></button>
            </div>
            <?php if($_GET['id'] == 1) { ?>
                <div class="create-success">
                    <h5>Usuário criado com sucesso.</h5>
                </div>
            <?php } if($_GET['id'] == 2) { ?>
                <div class="create-success">
                    <h5>Usuário atualizado com sucesso.</h5>
                </div>
            <?php }?>
            <table id="usersTable" class="pt-2 table table-hover table-bordered table-striped" align="center" border="0" cellpadding="2" cellspacing="1" width="99%">
                <thead style="background: #444; color:#fff;">
                    <tr>
                        <th style="text-align:center">
                            <input id="select-all" type="checkbox">
                        </th>
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
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Certeza que quer excluir os items selecionados?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button type="buttons" class="remove-escrito">Excluir</button>
                <button type="button" class="cancelar" data-bs-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
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
                { data: 'select'},
                { data: 'id' },
                { data: 'name' },
                { data: 'email' },
                { data: 'criado_em' },
                { data: 'atualizado_em' }
            ],
            columnDefs: [{
                targets: 0,
                orderable: false,
                className: 'select-checkbox'
            }],
            select: {
                info: false,
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[1,'asc']]
        });

        $('#usersTable').on('click','tbody tr td:not(:nth-child(1))',function(){
            location.href='useredit.php?id='+ $('tbody tr').children().eq(1).text();            
        });

        $('#select-all').on('click',function() {
            if(this.checked) {$('#usersTable tbody tr').addClass('selected') }
            else {($('#usersTable tbody tr').removeClass('selected'))}
        })

        $('.remove-escrito').on('click',function(){
            selecionados = [];
            $('.selected').each(function(){
                var selecionado = $(this).children().eq(1);
                selecionados.push(selecionado.text()); 
            })
            $.ajax({
                url:'api/remover-user.php',
                type: 'POST',
                data: {
                    ids: JSON.stringify(selecionados)
                }
            }).done(function(res){
                location.reload();
            })
        });


    });


</script>