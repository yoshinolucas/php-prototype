<?php
include_once '../../includes/config.php';
include_once '../../includes/header.php'; 
include_once '../../includes/top.php';
include_once '../../includes/sidebar.php';
protege();

$id = $_GET['id'];
if($id>0){
    $produto = mysql_fetchRow('SELECT * FROM produtos WHERE id=:id',
    Array(':id'=>$id));
}

if($_POST['form'] == 'Y'){
    if(
        $_POST['name'] == '' ||
        $_POST['marca'] == ''
    ) {
        $error_obrigatorio = "Verifique todos os campos obrigatórios.";
    } else {
        if($_POST['unidades'] == '') $_POST['unidades'] = 0;
        $active = $_POST['active'] == 'checked' ? 1 : 0;
        if($id>0){
            $run = mysql_fetch('UPDATE produtos SET name=:name,marca=:marca,unidades=:unidades,active=:active WHERE id=:id',
            Array(':id'=>$id,':name'=>$_POST['name'],
            ':marca'=>$_POST['marca'],':unidades'=>$_POST['unidades'],
            ':active'=>$active));
        } else {
            $run = mysql_fetch('INSERT INTO produtos (name,marca,unidades,active) 
            VALUES (:name, :marca, :unidades, :active)',
            Array(
                ':name'=>$_POST['name'],
                ':marca'=>$_POST['marca'],
                ':unidades'=>$_POST['unidades'],
                ':active'=>$active
            ));     
        } 
        if(!$run) {
            $error = "Erro interno. Por favor tentar novamente mais tarde.";
        } else {
            $success = $id>0?2:1;
            print "<script>location.href='/pages/estoques/produtos.php?id=".$success."'</script>";
            exit;             
        }
    }
}
?>


<section>
<div class="panel" id="panel">
    <a class="voltar" href="/pages/estoques/produtos.php">
        <i class="fa-solid fa-arrow-left">
        </i>
        Voltar
    </a>
    <?php if($error_obrigatorio!=''){?>
        <div class="error-required">
            <h5><?php print $error_obrigatorio ?></h5>
        </div>
    <?php }?>
    <?php if($error!=''){?>
        <div class="error-required">
            <h5><?php print $error ?></h5>
        </div>
    <?php }?>
    <div class="panel-header" style="margin-top:12px">
        <h4 class="panel-title"><?php print $id>0 ? 'Atualizar informações':'Novo Produto'?></h4>
    </div>
    <div class="panel-body">
        <div class="criar">
            <form method="POST">
                <input name='form' value='Y' hidden>
                <div>
                    <label>Nome do produto:</label>
                    <input type="text" value="<?php print $id>0 ? $produto['name'] : $_POST['name']?>" 
                    name="name" 
                    style="<?php if($error_obrigatorio!='' && empty($_POST['name'])) print "border: 1px solid red"?>">
                </div>
                <div>
                    <label>Marca:</label>
                    <input value="<?php print $id>0 ? $produto['marca'] : $_POST['marca']?>"
                    type="text" 
                    name="marca" 
                    style="<?php if($error_obrigatorio!='' && empty($_POST['marca'])) print "border: 1px solid red"?>">
                </div>
                <div>
                    <label>Unidades:</label>
                    <input 
                    value="<?php print $id>0?$produto['unidades']:(empty($_POST['unidades'])?0:$_POST['unidades'])?>"
                    type="number"
                    name="unidades">
                </div>
                <?php if($id>0){?>
                    <div>
                        <label>Data de cadastro:</label>
                        <input 
                        value="<?php print $produto['criado_em']?>"
                        type="text"
                        name="criado_em"
                        readonly>
                    </div>
                    <div>
                        <label>Última atualização:</label>
                        <input 
                        value="<?php print $produto['atualizado_em']?>"
                        type="text"
                        name="att_em"
                        readonly>
                    </div>
                <?php }?>
                <div>
                    <label>Ativo:</label>
                    <input 
                    class="checkbox"
                    type="checkbox"
                    value="checked"
                    name="active">
                </div>
                <button class="salvar" type="submit">Salvar</button>
                <a class="cancelar" href="/pages/estoques/produtos.php">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</section>
<script>
    $('.checkbox').prop('checked', <?php print $produto['active'] ? 'true' : 'false'?>);

</script>

<?php 
include '../../includes/footer.php';
?>