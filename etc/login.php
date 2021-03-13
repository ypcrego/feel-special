    <?php
    session_start();
    if(isset($_SESSION['email'])){
        header("Location: ../etc/index.php");
        exit();
     }
    require '../etc/banco.php';
 // print_r($_SESSION);
    ?>
<!DOCTYPE html>
<html>


<body>
<?php include_once "../etc/header.php"; ?>


    <main class="container">
        <br>
        <h3>Já tem uma conta? Faça login!</h3>
        <form method="POST" action="logar.php" id="formLogin" name="formLogin">
            <div class="form-group">
                <label for="email1">Endereço de email</label>
                <input type="email" class="form-control" name="email" id="email1" aria-describedby="emailHelp" placeholder="Seu email">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
            </div>
            <small id="dados-help" class="form-text text-muted">Nunca vamos compartilhar seus dados com ninguém.</small> <Br>
            <button type="submit" class="btn right #9ccc65 light-green lighten-1">Fazer login<i class="large material-icons right">send</i></button>
        </form>

        <br>
        <br>

        <h6>Caso não tenha uma conta, clique <a href="../usuario/cadastro.php">aqui</a> para criar uma.</h6>

        </form>

    </main>

    <?php include_once "../etc/footer.php"; ?>

</body>

</html>