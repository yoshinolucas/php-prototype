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



            <div class="table-header">


                <div class="filters">

                    <button id="filters" class="botao-theme01">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <div class="input-icon">
                        <input type="search" id="search" placeholder="Pesquisar...">
                    </div>
                    
                </div>          
                

                <div class="fast-menu">
                    <div id="mais-fast-menu" class="botao-group m-right" style="display:none">
                        <a class="botao-sm m-right" href="/pages/estoques/api/excel-produtos.php"><i class="fa-solid fa-file-pdf"></i></a>  
                        <a class="botao-sm" href="/pages/estoques/api/excel-produtos.php"><i class="fa-solid fa-file-lines"></i></a>   
                    </div>
                    <i style="cursor:pointer; align-self:center" id="expand-mais-fast-menu" class="fa-solid fa-angle-left m-right"></i>
                    <div class="botao-group">
                        <a id="copy" class="botao-sm"><i class="fa fa-copy"></i></a>
                        <button class="botao-sm botao-theme04 respiro-x" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-trash"></i></button>
                        <a class="botao-sm botao-theme01" href="/pages/estoques/produtoedit.php?id=0"><i class="fa fa-add"></i></a>
                        <button id="refresh" class="botao-sm m-left"><i class="fa fa-refresh"></i></button>

                    </div>
                </div>

            </div>


            <div class="filters-menu">
                <div class="m-bottom"> 
                    <label>Período: </label>
                    <div class="ipt-group">
                        <select id="per">
                            <option value="todos">Todos</option>
                            <option value="hoje">Hoje</option>
                            <option value="ontem">Ontem</option>
                            <option value="semana">Últimos 7 dias</option>
                            <option value="mes">Últimos 30 dias</option>
                            <option value="meio-ano">Últimos 180 dias</option>
                        </select>
                        <span>&nbsp;Ou&nbsp;</span>
                        <div>
                            <input id="min" type="date">
                        </div>
                        <span>&nbsp;á&nbsp;</span>
                        <div>
                            <input id="max" type="date">
                        </div>
                    </div>
                    
                    

                </div>
                <div class="m-bottom">
                    <label>Marca: </label>
                    <select id="marca">
                        <option value="todos">Todos</option>
                        <?php
                            $produtos = mysql_fetchAll('SELECT DISTINCT marca FROM produtos');
                            foreach($produtos as $produto){
                                print "<option value='".$produto['marca']."'>".$produto['marca']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="m-bottom">
                    <label>Categoria: </label>
                    <select id="categoria">
                        <option value="todos">Todos</option>
                    </select>
                </div>


                <div>
                    
                    <button id="filter-submit" class="botao-sm botao-theme02">Aplicar</button>
                    <button id="filter-clear" class="botao-sm botao-theme03" >Limpar</button>

                </div>
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
    $(document).ready(function(){
        $('#filters-menu').hide();
    })
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
            type: 'POST',
            data: function( d ) {
                d.per = $('#per').val();
                d.marca = $('#marca').val();
                d.categoria = $('#categoria').val();
            }
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

    $('#copy').on('click',function(){
        selecionado = getSelecionados();
        if(selecionado.length == 1) {
            location.href="/pages/estoques/produtoedit.php?id="+selecionado[0]+"&copy=1";
        } else {
            alert('Escolha no máximo 1 produto');
        }
    });

    $('#filters').on('click', function(){
        $('.filters-menu').slideToggle();
    });

    $('#filter-submit, #refresh').on('click', function(){
        table.draw();
    });


    $('#min').on('click', function(){
        $('#min').removeAttr('readonly');
        $('#max').removeAttr('readonly');
        $('#per').attr('readonly', 'true');
    });

    $('#per').on('click', function(){
        $('#per').removeAttr('readonly');
        $('#min').attr('readonly', 'true');
        $('#max').attr('readonly', 'true');
    })

    $('#filter-clear').on('click', function(){
        $('#per').val('todos');
        $('#min').val('');
        $('#max').val('todos');
        $('#marca').val('todos');
        $('#categoria').val('todos');
    });

    $('#expand-mais-fast-menu').on('click',function(){
        $('#mais-fast-menu').animate({width:'toggle'},350, function(){
            $("#expand-mais-fast-menu").toggleClass('fa-rotate-180');
        });
        
    });

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