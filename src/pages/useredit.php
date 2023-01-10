<?php
include_once '../includes/config.php';
include_once '../includes/header.php'; 
include_once '../includes/top.php';
include_once '../includes/sidebar.php';
protege();

$id = $_GET['id'];
if($id>0){
    $user = mysql_fetchRow('SELECT * FROM users WHERE id=:id',
    Array(':id'=>$id));
}

if($_POST['form'] == 'Y'){
    if(
        $_POST['name'] == '' ||
        $_POST['email'] == '' ||
        $_POST['pass'] == ''
    ) {
        $error_obrigatorio = "Verifique todos os campos obrigatórios.";
    } else {
        $exist = mysql_fetchRow("SELECT email FROM users WHERE email = :email", Array(":email"=>$_POST['email']));
        if($id>0? $exist['email'] != $user['email'] : $_POST['email'] == $exist['email']){
            $error = "Email já cadastrado no sistema.";
            $email_exist = true;
        } else {
            if($id>0){
                $run = mysql_fetch('UPDATE users SET name=:name,email=:email,password=:pass WHERE id=:id',
                Array(':id'=>$id,':name'=>$_POST['name'],':email'=>$_POST['email'],':pass'=>$_POST['pass']));
            } else {
                $run = mysql_fetch('INSERT INTO users (name,email,password) 
                VALUES (:name, :email, :pass)',
                Array(
                    ':name'=>$_POST['name'],
                    ':email'=>$_POST['email'],
                    ':pass'=>$_POST['pass']
                ));     
            } 
            if(!$run) {
                $error = "Erro interno. Por favor tentar novamente mais tarde.";
            } else {
                $success = $id>0?2:1;
                print "<script>location.href='./users.php?id=".$success."'</script>";
                exit;
            }   
        }
    }
}
?>


<section>
<div class="panel" id="panel">
    <a class="voltar" href="/pages/users.php">
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
        <h4 class="panel-title">Criar Usuário</h4>
    </div>
    <div class="panel-body">
        <div class="criar">
            <form method="POST">
                <input name='form' value='Y' hidden>
                <div>
                    <label>Nome:</label>
                    <input type="text" value="<?php print $id>0 ? $user['name'] : $_POST['name']?>" 
                    name="name" 
                    style="<?php if($error_obrigatorio!='' && empty($_POST['name'])) print "border: 1px solid red"?>">
                </div>
                <div>
                    <label>E-mail:</label>
                    <input value="<?php print $id>0 ? $user['email'] : $_POST['email']?>"
                    type="email" 
                    name="email" 
                    style="<?php if($email_exist || $error_obrigatorio!='' && empty($_POST['email'])) print "border: 1px solid red"?>">
                </div>
                <div>
                    <label>Senha:</label>
                    <input type="password"
                    name="pass"
                    style="<?php if($error_obrigatorio!='' && empty($_POST['pass'])) print "border: 1px solid red"?>">
                </div>
                <button class="salvar" type="submit">Salvar</button>
                <a class="cancelar" href="/pages/users.php">Cancelar</a>
            </form>
        </div>
       
    </div>

    

</div>
</section>


<?php 
include '../includes/footer.php';
?>