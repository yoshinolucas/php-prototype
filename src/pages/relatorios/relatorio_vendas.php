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
            <h4 class="panel-title">Vendas</h4>
        </div>
        <div class="panel-body">
            <table id="salesTable" class="pt-2 table table-hover table-bordered table-striped" align="center" border="0" cellpadding="2" cellspacing="1" width="99%">
                <thead style="background: #444; color:#fff;">
                    <tr>
                        <th style="text-align:center">
                            <input id="select-all" type="checkbox">
                        </th>
                        <th>Id</th>
                        <th>Vendedor</th>
                        <th>Data da venda</th>
                    </tr>
                </thead>
                <tbody>              
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php 
include_once '../../includes/footer.php';
?>

<script>

$(document).ready( function () {
        var table = $('#salesTable').DataTable({     
            responsive:true,
            processing: true,
            serverSide: true,
            ajax: {
                url:'api/vendas.datatables.php',
                type: 'POST'
            },
            language: {
                url: '/locale/dataTable.json'
            },
            columns: [
                { data: 'select'},
                { data: 'id' },
                { data: 'vendedor' },
                { data: 'vendido_em' },
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

        $('#select-all').on('click',function() {
            if(this.checked) {$('#salesTable tbody tr').addClass('selected') }
            else {($('#salesTable tbody tr').removeClass('selected'))}
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
                url:'api/remover-venda.php',
                type: 'POST',
                data: {
                    ids: JSON.stringify(getSelecionados())
                }
            }).done(function(res){
                location.reload();
            })
        });


    });


</script>