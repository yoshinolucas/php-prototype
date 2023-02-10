<?php
define('ROOT',$_SERVER['DOCUMENT_ROOT']);
include_once ROOT."/includes/config.php";
include_once ROOT."/includes/header.php";
include_once ROOT."/includes/top.php";
include_once ROOT."/includes/sidebar.php";
protege();

$id = $_GET['id'];
$mesas = mysql_fetchAll('SELECT * FROM mesas WHERE restaurante = :id', Array(':id'=>$id));
$now = date('Y-m-d H:i:s');

$mesas_ocupadas = Array();
if ($_POST['form'] == 'Y') {

    if(isset($_POST['remove'])) {
        $verifica_status = mysql_fetchRow('SELECT status FROM mesas WHERE restaurante = :id ORDER BY data DESC LIMIT 1',
        Array(':id'=>$id));
        if($verifica_status['status'] == 1){
            $run = mysql_fetch('DELETE FROM mesas WHERE restaurante = :id ORDER BY data DESC LIMIT 1',
            Array(':id'=>$id));
        } else {
            $error_msg = "Por favor, verifique o status da última mesa";
        }
    } else {
        $run = mysql_fetch('INSERT mesas (restaurante, status) VALUES (:id, :status)',
        Array(
            ':id' => $id,
            ':status' => 1,
        ));
    }

    if($run) print "<script>location.href='mesas.php?id=".$id."'</script>";
    
}

if($_POST['status'] == 'Y') {
    $already = mysql_fetchRow('SELECT status, ocupado FROM mesas WHERE id=:id', Array(':id'=>$_POST['mesa-id']));
    
    $status = $_POST['disponivel'] == 1 ? 1 : 0;
    $ocupado =  $_POST['disponivel'] == 1 ? '0000-00-00 00:00:00' : 
    ($already['status'] == 1 ? $now : $already['ocupado']);


    $run = mysql_fetch('UPDATE mesas SET status = :status, ocupado = :ocupado WHERE id = :id',
    Array(
        ':status' => $status,
        ':id' => $_POST['mesa-id'],
        ':ocupado' => $ocupado
    ));     
    
    print "<script>location.href='mesas.php?id=".$id."'</script>";
}
?>

<section class="restaurant">
    <div class="panel" id="panel">
        <div class="panel-header">
            <?php include_once ROOT."/includes/restaurant-nav.php"; ?>
        </div>
        <div class="panel-body">
            <?php if(!empty($error_msg)) {?>
                <div class="error-required"><?php print $error_msg ?></div>
            <?php }?>
            <div class="mesas">
                <div class="mesas-lista">
                    <div class="mesas-header">
                        <h3>Escolha uma mesa</h3>
                        <form method="post">
                            <input hidden name="form" value="Y">
                            <div style="display:flex">
                                <button type="submit" class="botao-sm botao-theme04 m-right" name="remove">Remover mesa&nbsp;<i class="fa fa-trash"></i></button>
                                <button type="submit" class="botao-sm botao-theme01" name="add">Cadastrar mesa&nbsp;<i class="fa fa-add"></i></button>
                            </div>
                            
                        </form>
                    </div>
                    <div class="mesas-body">
                        <ul>
                            <?php 
                                if(count($mesas) > 0){
                                    
                                    $i = 1;
                                    foreach($mesas as $mesa){ 
                                        $status = $mesa['status'];
                                        if($status == 0) $mesas_ocupadas[] = Array('numero'=>$i, 'data'=>$mesa['ocupado']);
                                        $class = $status == 1 ? 'disponivel' : 'ocupado'; 
                                        print "<li><button value=".$mesa['id']." id='open-modal".$i."' class='mesa ".$class."'>".$i."</button></li>";
                                        $i++;
                                    }
                                } else {
                                    print "<h3>Nenhuma mesa cadastrada.</h3>";
                                }
                            ?>  
                        </ul>
                    </div>
                </div>
                <div class="mesas-ocupadas">
                    <div class="mesas-header">
                        <h3>Tempo de mesas ocupadas</h3>
                    </div>
                    <table id="mesasOcupadas" class="table01" align="center" border="0" cellpadding="2" cellspacing="1" width="99%">
                        <thead>
                            <tr>
                                <th>Mesa</th>
                                <th>Tempo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $dtnow = new DateTime($now);
                                foreach($mesas_ocupadas as $mesa_ocupada){
                                    $data_ocupado = new DateTime($mesa_ocupada['data']);
                                    $tempo_ocupado = $dtnow->diff($data_ocupado);
                                    $horas = $tempo_ocupado->format('%H');
                                    $minutos = $tempo_ocupado->format('%I');
                                    $segundos = $tempo_ocupado->format('%S');
                                    print "<tr>";
                                    print "<td>".$mesa_ocupada['numero']."</td>";
                                    print "<td><div><span id='hour".$mesa_ocupada['numero']."'>".$horas."</span>:<span id='minute".$mesa_ocupada['numero']."'>".$minutos."</span>:<span id='second".$mesa_ocupada['numero']."'>".$segundos."</span></div></td>";
                                    print "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-mesa">                          
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <span class="modal-close" id="modal-close">&times;</span>
            </div>
            <form method="post">
                <input name="status" value="Y" hidden>
                <div class="modal-body">
                    <label>Status atual</label>

                    <div class="modal-status">
                        <input name="mesa-id" hidden>
                        <button type="button" id="disponivel" class="botao-sm botao-theme02">Disponível</button>
                        <button type="button" id="ocupado"  class="botao-sm botao-theme04">Ocupado</button>
                        <input name="disponivel" hidden> 
                    </div>
                    

                </div>
                <div class="modal-footer">
                    <button type="submit" class="botao-sm botao-theme01">Salvar</button>
                    <button class="botao-sm botao-theme03" id="modal-close-cancel">Voltar</button>
                </div>
            </form>

        </div>

    </div>

</section>



<?php 
include_once ROOT."/includes/footer.php";
?>

<script>

    var table = $('#mesasOcupadas').DataTable({
        responsive:{
            details: false
        },
        info: false,
        lengthChange:false,
    });

   
    var mesa;
    const modal = $("#modal-mesa");

    $("button[id^='open-modal']").on('click', (e) => {
        mesa = e.target.innerText;
        modal.css('display', 'block');
        $("input[name='mesa-id']").val(e.target.value);
        $(".modal-title").append(document.createTextNode('N°' + mesa));
        e.target.classList.contains('disponivel') ? disponivel(true) : disponivel(false);
    });

    function dropModal() {
        $('.modal-title').empty();
        $('.modal-msg').empty();
        $("input[name='mesa-id']").val('');
        modal.css('display', 'none');
    }


    function disponivel(bool){  
        if(bool) {
            $("#ocupado").addClass('status-unselected');
            $("#disponivel").removeClass('status-unselected');
            $("input[name='disponivel']").val(1);
        } else {
            $("#disponivel").addClass('status-unselected');
            $("#ocupado").removeClass('status-unselected');
            $("input[name='disponivel']").val(0);
        }
    }  

    $("#disponivel").on('click',function(){
        disponivel(true);
    });

    $("#ocupado").on('click',function(){
       disponivel(false);
    });

    $("#modal-close, #modal-close-cancel").on('click', () => { 
        dropModal() 
    });

    window.onclick = (event) => {
        if (event.target == modal[0]) dropModal() 
    }

    
    function attTimer(item, i){
        var seconds = item.substr(6);
        var minutes = item.substr(3,2);
        var hours = item.substr(0,2);
        seconds++;
        i++;
        if(seconds == 60){
            seconds = 0;
            minutes++;
        }

        if(minutes == 60){
            minutes = 0
            hours++;
        }

        if(seconds[0]==0) seconds = seconds[1];
        
        if(minutes[0]==0) minutes = minutes[1];

        if(hours[0]==0) hours = hours[1];

        if(minutes>5 && minutes < 10) $("#mesasOcupadas tbody tr:nth-child("+i+")").css('background-color','rgba(252, 151, 0, 0.986)');
        if(minutes>10) $("#mesasOcupadas tbody tr:nth-child("+i+")").css('background-color','rgb(218, 65, 86)');

        $("#mesasOcupadas tbody tr:nth-child("+i+") td:nth-child(2) div span[id^='minute']")
        .text(returnData(minutes))

        $("#mesasOcupadas tbody tr:nth-child("+i+") td:nth-child(2) div span[id^='second']")
        .text(returnData(seconds)) 

        $("#mesasOcupadas tbody tr:nth-child("+i+") td:nth-child(2) div span[id^='hour']")
        .text(returnData(hours)) 
    }
    
    function returnData(input) {
        return input >= 10 ? input : `0${input}`;
    }

    setInterval(function() { 
        var mesas = $('#mesasOcupadas tbody tr td:nth-child(2)').text();
        mesas = mesas.match(/.{1,8}/g);
        mesas.forEach(attTimer);
     }, 1000);
    
    

</script>