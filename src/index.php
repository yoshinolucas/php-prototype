<?php 
include_once 'includes/header.php';

if($_POST['form'] == 'Y'){
    $email = $_POST['email'];
    $pass = $_POST['senha'];
    if(
        $email == '' ||
        $pass == ''
    ) {
        $error = 'Digite os campos obrigatórios.';
    } else {
        $res = mysql_fetchRow("SELECT id, email, password FROM users WHERE email=:email", Array(":email"=>$email));
        if(!$res) {
            $error = 'Email ou senha incorretos.';
        } else {
            print "<script>location.href='./pages/home.php?id=".urlencode($res['id'])."'</script>";
            exit;
        }
    }
}
?>

<section class="main-login">
    <?php if($error) {?>
            <div class="error">
                <h5><?php print $error; ?></h4>
            </div>
    <?php } ?>
    <div class="login"> 
        <form method="POST">
            <input value="Y" name="form" hidden>
            <div>
                <label>Email</label>
                <input type="text" name="email" placeholder="Digite seu email.">
            </div>
            <div>
                <label>Senha</label>
                <input type="password" name="senha" placeholder="Digite sua senha.">
            </div>
            <button type="submit">Entrar</button>
        </form>     
    </div>
</section>
<?php 
include 'includes/footer.php'; 
?>