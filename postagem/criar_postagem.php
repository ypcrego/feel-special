<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location:../etc/login.php");
}

$id_postagem = null;
if (!empty($_SESSION['id_usuario'])) {
    $id_postagem = $_SESSION['id_usuario'];
}
$usuario_livre = $_SESSION['usuario_livre'];


/*  $titulo = "";
    $conteudo = "";
    $descricao = "";
    $tags = ""; */
require '../etc/banco.php';
//   print_r($_SESSION);

//  && ($_POST['privado'] == 'on' || $_POST['privado'] == 'off') 
if (!empty($_POST)) {
    //Acompanha os erros de validação


    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $descricao = $_POST['descricao'];
    $tags = $_POST['tags'];
    if ($usuario_livre == 1) {
        $privado = $_POST['privado'];
    } else {
        $privado = 1;
    }



    /* $_SESSION['titulo'] = $titulo;
        $_SESSION['conteudo'] = $conteudo;
        $_SESSION['descricao'] = $descricao;
        $_SESSION['tags'] = $tags; */



    //Validaçao dos campos:
    $validacao = true;
    if (empty($titulo)) {
        $validacao = false;
    }


    if (empty($conteudo)) {
        $validacao = false;
    }

    //Inserindo no Banco:
    if ($validacao) {

        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO postagem (id_usuario, titulo, conteudo, descricao, tags, privado, denuncias) VALUES(?,?,?,?,?,?,0)";
        $q = $pdo->prepare($sql);
        $q->execute(array($id_postagem, $titulo, $conteudo, $descricao, $tags, $privado));

        Banco::desconectar();
        header("Location: ../usuario/perfil.php");
    }
}
?>

<!DOCTYPE html>
<html>



<body>
    <?php include_once "../etc/header.php"; ?>


    <main class="container">

        <form action="criar_postagem.php" method="post">
            <h3> Criar postagem </h3>
            <div class="divider"></div>
            <h3>Título:</h3>
            <div class="row">
                <div class="input-field col s12">

                    <input class="form-control form-control-lg" name="titulo" type="text" placeholder="Escreva aqui o título para a sua postagem!" required oninvalid="this.setCustomValidity('Por favor, insira o título!')" oninput="this.setCustomValidity('')">
                    <label class="active" for="inputTitulo">Título:</label>
                </div>
            </div>
            <h3>Descrição:</h3>
            <div class="row">
                <div class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="textareadescricao" class="materialize-textarea" name="descricao" placeholder="Escreva uma descrição para a sua postagem!"></textarea>
                            <label class="active" for="textareadescricao">Descrição:</label>
                        </div>
                    </div>
                </div>
            </div>

            <h3>Conteúdo:</h3>
            <div class="row">
                <div class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="textareaconteudo" class="materialize-textarea" name="conteudo" placeholder="Escreva aqui o conteúdo da postagem!" required oninvalid="this.setCustomValidity('Por favor, insira o conteúdo!')" oninput="this.setCustomValidity('')"></textarea>
                            <label class="active" for="textareaconteudo">Conteúdo:</label>
                        </div>
                    </div>
                </div>
            </div>
            <h4>Tags:</h4>

            <div class="row">
                <div class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="textareatags" class="materialize-textarea" name="tags" placeholder="Digite tags para facilitar que sua postagem seja encontrada! Exemplo: #tag"></textarea>
                            <label class="active" for="textareatags">Tags:</label>
                        </div>
                    </div>
                </div>
            </div>


            <?php if ($usuario_livre == 0) echo '<br><blockquote>Você recebeu muitas denúncias e só pode criar postagens privadas.</blockquote>'; ?>
            <div class="row valign-wrapper">
                <p>
                    <label>
                        <?php if ($usuario_livre == 1) { ?>
                            <input type="hidden" name="privado" value="0" id="default">
                            <input type="checkbox" checked="checked" name="privado" value="1" id="privadasim" />
                            <span>Tornar privada</span>
                        <?php } else {
                        ?>
                            <input type="hidden" name="privado" value="1" id="privadasim" />
                            <input type="checkbox" disabled="disabled" checked="checked" name="privado" value="1" id="privadasim" />
                            <span>Tornar privada</span>
                        <?php } ?>
                    </label>

                </p>

            </div>
            <br />
            <div class="row valign-wrapper">

                <div class="col s8">
                    <a href="../usuario/perfil.php" class="btn waves-effect waves-light #9ccc65 light-green lighten-1">Cancelar</a>
                </div>

                <div class="col s3">
                    <button class="btn waves-effect waves-light #9ccc65 light-green lighten-1" type="submit" name="action" value="any_value">Enviar
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>

        </form>
    </main>
    <br>
    <?php include_once "../etc/footer.php"; ?>


</body>

</html>