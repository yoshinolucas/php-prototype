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
                        <input type="text" class="search" id="search" placeholder="Pesquisar...">
                    </div>
                    
                </div>          
                

                <div class="fast-menu">
                    <div class="botao-group">
                        <a id="copy" class="botao-sm"><i class="fa fa-copy"></i></a>
                        <button id="open-modal" class="botao-sm botao-theme04 respiro-x"><i class="fa fa-trash"></i></button>
                        <a class="botao-sm botao-theme01" href="/pages/estoques/produtoedit.php?id=0"><i class="fa fa-add"></i></a>
                        <button id="refresh" class="botao-sm m-left"><i class="fa fa-refresh"></i></button>

                    </div>
                </div>

            </div>


            <div class="filters-menu">
                <h3>Filtros</h3>
                <div class="respiro-y"> 
                    <label>Período: </label>
                    <div id="periodo">
                        <select id="per">
                            <option value="todos">Todos</option>
                            <option value="hoje">Hoje</option>
                            <option value="ontem">Ontem</option>
                            <option value="semana">Últimos 7 dias</option>
                            <option value="mes">Últimos 30 dias</option>
                            <option value="meio-ano">Últimos 180 dias</option>
                        </select>
                        <div>
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
                </div>

                <div class="m-bottom">
                        <label>Marca: </label>
                        <div>
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
                    </div>
                    <div class="m-bottom">
                        <label>Categoria: </label>
                        <div>
                            <select id="categoria">
                                <option value="todos">Todos</option>
                            </select>
                        </div>                    
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
                            <input id="select-all" class="checkbox" type="checkbox">
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

    <div class="modal" id="modal-remove">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remover produtos</h5>
                <span class="modal-close" id="modal-close">&times;</span>
            </div>
            <div class="modal-body">
                <h5 class="modal-msg"></h5>
            </div>
            <div class="modal-footer">
                <button class="botao-sm botao-theme03" id="modal-close-cancel">Cancelar</button>
                <button class="botao-sm botao-theme04" id="modal-submit">Excluir</button>
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
            type: 'POST',
            data: ( d ) => {
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
    
    $('#copy').on('click',() => {
        selecionado = getSelecionados();
        if(selecionado.length == 1) {
            location.href="/pages/estoques/produtoedit.php?id="+selecionado[0]+"&copy=1";
        } else {
            alert('Escolha no máximo 1 produto');
        }
    });

    $('#produtosTable').on('click','tbody tr td:first-child', function() {
        var linha = $(this).parent()
        if(linha.hasClass('selected')){
            linha.removeClass('selected');
        } else {
            linha.addClass('selected');
        }
        var selecionados = getSelecionados();
        if(selecionados.length==10) {
            $('#select-all').prop('checked',true);
        } else {
            $('#select-all').prop('checked',false);
        }
    })

    $('#filters').on('click', () => {
        $('.filters-menu').slideToggle();
    });

    $('#filter-submit, #refresh').on('click', () => {
        $('#select-all').prop('checked',false);
        table.draw();
    });


    $('#min').on('click', () => {
        $('#min').removeAttr('readonly');
        $('#max').removeAttr('readonly');
        $('#per').attr('readonly', 'true');
    });

    $('#per').on('click', () => {
        $('#per').removeAttr('readonly');
        $('#min').attr('readonly', 'true');
        $('#max').attr('readonly', 'true');
    })

    $('#filter-clear').on('click', () => {
        $('#per').val('todos');
        $('#min').val('');
        $('#max').val('todos');
        $('#marca').val('todos');
        $('#categoria').val('todos');
    });

    function getSelecionados() {
        selecionados = [];
        $('.selected').each(function(){
            var selecionado = $(this).children().eq(1);
            selecionados.push(selecionado.text()); 
        })
        return selecionados;
    }

    function dropModal() {
        $('.modal-msg').empty()
        modal.css('display', 'none') 
    }

    var modal = $('#modal-remove');
    $('#open-modal').on('click', () => { 
        if(getSelecionados().length==0) {
            alert('Escolha pelo menos 1 produto');
        } else {
            modal.css('display', 'block')
            var msg = 'Deseja mesmo remover ' + getSelecionados().length + ' produto(s)?';
            $('.modal-msg').append(document.createTextNode(msg));
        }
    });

    $("#modal-close, #modal-close-cancel").on('click', () => { 
        dropModal() 
    })
    window.onclick = (event) => {
        if (event.target == modal[0]) {
            dropModal()
        }
    }

    $('#modal-submit').on('click',()=>{
        $.ajax({
            url:'api/remover-produtos.php',
            type: 'POST',
            data: {
                ids: JSON.stringify(getSelecionados())
            }
        }).done((res) => {
            table.draw();
            dropModal();
        })
    });
</script>