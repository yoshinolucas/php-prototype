<?php 
include 'includes/config.php';
include 'includes/header.php';

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
            print "<script>location.href='./src/pages/home.php?id=".urlencode($res['id'])."'</script>";
            exit;
        }
    } 
}
?>

<section>
    <div class="login">
        <?php if($error) {?>
            <div class="error">
                <p><?php print $error; ?></p>
            </div>
        <?php }?>
        <form method="POST">
            <input value="Y" name="form" hidden>
            <div>
                <label>Email</label>
                <input type="text" name="email">
            </div>
            <div>
                <label>Senha</label>
                <input type="password" name="senha">
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</section>
<?php 
include 'includes/footer.php'; 
?>