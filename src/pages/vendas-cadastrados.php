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
                <div>
                    <a class="voltar" href="/pages/vendas.php">
                        <i class="fa-solid fa-arrow-left">
                        </i>
                        Voltar
                    </a>
                    <h4 class="panel-title">Registrar vendas | Passo(2/4) / Escolha um cliente</h4>
                </div>
            </div>
            <div class="panel-body">
                
                <div class="vendas">
                    <div class="clientes">
                        <h5 class="panel-title" style="text-align:center">Tabela de clientes</h5>
                        <table id="clientesTable" class="pt-2 table table-hover table-bordered table-striped" width="100%">
                            <thead style="background: #444; color:#fff;">
                                <tr>
                                    <th></th>
                                    <th>Id</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Data de cadastro</th>
                                    <th>Última atualização</th>
                                </tr>
                            </thead>
                            <tbody>              
                            </tbody>
                        </table>
                    </div>        
                    <div class="menu-vendas">
                        <div class="lista">
                            <ul>
                                <li>
                                    <img>
                                </li>
                                <li><button><i class="fa fa-edit"></i>Editar</button></li>
                                <li><input class="desc" name="desc" onclick="getDesconto()" value="checked" type="checkbox"><label style="margin-left:4px">Aplicar Desconto</label></li>
                                <li hidden><label>%</label><input type="number" id="inp" name="desc-value"></li>
                                <li><input class="cupom" name="cupom" onclick="getCupom()" value="checked" type="checkbox"><label style="margin-left:4px">Aplicar Cupom</label></li>
                                <li hidden><input type="text" id="desc-cupom" name="desc-cupom"></li>
                            </ul>
                        </div>         
                        <a class="add">Avançar</a>             
                    </div>    
                </div>
            </div>
        </div>
    </section>

<?php 
include '../includes/footer.php';
?>

<script>
    function getDesconto() {
        if ($('input.desc').is(':checked')) { $(".lista ul li:nth-child(4n)").prop("hidden", false)}
        else {
            $(".lista ul li:nth-child(4n)").prop("hidden", true)
        }
    }

    function getCupom() {
        if ($('input.cupom').is(':checked')) { $(".lista ul li:nth-child(6n)").prop("hidden", false)}
        else {
            $(".lista ul li:nth-child(6n)").prop("hidden", true)
        }
    }

    $(document).ready(function () {
        document.getElementById("inp").addEventListener("change", function() {
            let v = parseInt(this.value);
            if (v < 1) this.value = 1;
            if (v > 100) this.value = 100;
        });

        

        var table = $('#clientesTable').DataTable({
            processing: true,
            serverSide: true,
            info: false,
            lengthChange:false,
            ajax: {
                url:'pessoas/api/clientes.datatables.php',
                type: 'POST'
            },
            language: {
                url: '/locale/dataTable.json'
            },
            columns: [
                {data:'select'},
                {data:'id'},
                {data:'name'},
                {data:'email'},
                {data:'telefone'},
                {data:'criado_em'},
                {data:'atualizado_em'}
            ],
            columnDefs:[{
                targets:0,
                orderable: false,
                className: 'select-checkbox'
            },{
                targets:4,
                visible: false
            },{
                targets:5,
                visible: false
            },{
                targets:6,
                visible: false
            }],
            order:[[1,'asc']]
        })

        function verificaSelected(obj) {
            var len = $('.selected').length;
            if(len){
                $('#id').val(table.row(obj).data()['id'])
                $('#name').val(table.row(obj).data()['name']);
                $('#email').val(table.row(obj).data()['email']);
                $('#tel').val(table.row(obj).data()['telefone']);
                $('#criado_em').val(table.row(obj).data()['criado_em']);
                $('#atualizado_em').val(table.row(obj).data()['atualizado_em']);
            } else {  
                $('#id').val('');
                $('#name').val('');
                $('#email').val('');
                $('#tel').val('');
                $('#criado_em').val('');
                $('#atualizado_em').val('');     
            }
        }

        $('#clientesTable tbody').on('click', 'tr',function(){ 
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            event.stopPropagation();
            verificaSelected(this);
        })

        $('body').on('click', function () {
            table.$('tr.selected').removeClass('selected');
            verificaSelected(this);
        });

        $('.add').on('click', function(){
            if($('#id').val() != '') {
                location.href="/pages/vendas-produto.php?id=" + $('#id').val()
            }
        })


        
    });
</script>