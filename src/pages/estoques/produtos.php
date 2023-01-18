<?php
include_once '../../includes/config.php';
include_once '../../includes/header.php'; 
include_once '../../includes/top.php';
include_once '../../includes/sidebar.php';

protege();
?>

<section>
    <div class="panel" id="panel">
        <div class="panel-header">
            <h4 class="panel-title">Produtos</h4>
            <div class="botao-group">
                <a href="/pages/estoques/api/excel-produtos.php" class="botao"><i class="fa-solid fa-file-excel"></i></a>
                <button class="botao botao-theme04 respiro-x" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-trash"></i></button>
                <a class="botao botao-theme01" href="/pages/estoques/produtoedit.php?id=0"><i class="fa fa-add"></i></a>
            </div>
        </div>
        <div class="panel-body">
            <?php if($_GET['id'] == 1) { ?>
                <div class="create-success">
                    <h5>Produto criado com sucesso.</h5>
                </div>
            <?php } if($_GET['id'] == 2) { ?>
                <div class="create-success">
                    <h5>Produto atualizado com sucesso.</h5>
                </div>
            <?php }?>


            <div class="input-icon">
                <input type="search" id="search" placeholder="Pesquisar...">
            </div>
            

            
            <table class="table01" id="produtosTable" class="pt-2" align="center" border="0" cellpadding="2" cellspacing="1" width="99%">
                <thead>
                    <tr>
                        <th style="text-align:center">
                            <input id="select-all" type="checkbox">
                        </th>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Marca</th>
                        <th>Unidades</th>
                        <th>Data de cadastro</th>
                        <th>Última atualização</th>
                        <th>Ativo</th>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Certeza que quer excluir os<span></span> itens selecionados?</h1>
                <button type="button" class="remove-escrito">Excluir</button>
                <button type="button" class="cancelar" data-bs-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
    </div>
</section>


<?php 
include_once '../../includes/footer.php';
?>

<script>
    var table = $('#produtosTable').DataTable({     
        responsive:{
            details: false
        },
        processing: true,
        serverSide: true,
        info: false,
        lengthChange:false,
        ajax: {
            url:'api/produtos.datatables.php',
            type: 'POST'
        },
        language: {
            url: '/locale/dataTable.json'
        },
        columns: [
            { data: 'select'},
            { data: 'id' },
            { data: 'name' },
            { data: 'marca' },
            { data: 'unidades' },
            { data: 'criado_em' },
            { data: 'atualizado_em' },
            { data: 'active' },
        ],
        columnDefs: [{
            targets: 0,
            orderable: false,
            className: 'select-checkbox'
        }],
        select: {
            info: false,
            style: 'multi+shift',
            selector: 'td:first-child'
        },
        order: [[1,'asc']]
    });

    $('#search').keyup(function(){
        table.search($(this).val()).draw() ;
    })

    $('#produtosTable').on('click','tbody tr td:not(:nth-child(1))',function(){
        location.href='produtoedit.php?id='+ $(this).parent().attr('id');            
    });    
    $('#select-all').on('click',function() {
        if(this.checked) {$('#produtosTable tbody tr').addClass('selected') }
        else {($('#produtosTable tbody tr').removeClass('selected'))}
    })

    function getSelecionados() {
        selecionados = [];
        $('.selected').each(function(){
            var selecionado = $(this).children().eq(1);
            selecionados.push(selecionado.text()); 
        })
        return selecionados;
    }

    $('.remove-escrito').on('click',function(){
        $.ajax({
            url:'api/remover-produtos.php',
            type: 'POST',
            data: {
                ids: JSON.stringify(getSelecionados())
            }
        }).done(function(res){
            location.reload();
        })
    });


</script>