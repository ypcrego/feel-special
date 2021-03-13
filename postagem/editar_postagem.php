<?php
session_start();
require '../etc/banco.php';



if (!isset($_SESSION['id_usuario'])) {
    header("Location:../etc/login.php");
}

include_once "../etc/header.php";
$usuario_livre = $_SESSION['usuario_livre'];
$id_postagem = $_REQUEST['id_postagem'];



/* if (null == $id) {
        header("Location: ../etc/index.php");
    }
    */

if (!empty($_POST)) {

    $tituloErro = null;
    $descricaoErro = null;
    $conteudoErro = null;
    $tagsErro = null;
    // Seção para pegar os dados editados do formulário
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $conteudo = $_POST['conteudo'];
    $tags = $_POST['tags'];
    if ($usuario_livre == 1) { // usuário_livre == 1 e está livre
        $privado = $_POST['privado'];
    } else {
        $privado = 1; // nesse caso usuário_livre == 0 e está preso; postagem sempre será privada
    }
    //Validação
    $validacao = true;
    if (empty($titulo)) {
        $tituloErro = 'Por favor digite o título!';
        $validacao = false;
    }

    if (empty($conteudo)) {
        $conteudoErro = 'Por favor digite o conteúdo!';
        $validacao = false;
    }

    // update data
    if ($validacao) {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE postagem set titulo = ?, conteudo = ?, descricao = ?, tags = ?, privado = ? WHERE id_postagem = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($titulo, $conteudo, $descricao, $tags, $privado, $id_postagem));
        $_POST['titulo'] = $titulo;
        $_POST['conteudo'] = $conteudo;
        $_POST['descricao'] = $descricao;
        $_POST['tags'] = $tags;
        $_POST['privado'] = $privado;
        $_POST['id_postagem'] = $id_postagem;
        Banco::desconectar();
        header("Location: ../usuario/perfil.php");
    }
} else {
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM postagem where id_postagem = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id_postagem));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $titulo = $data['titulo'];
    $conteudo = $data['conteudo'];
    $descricao = $data['descricao'];
    $tags = $data['tags'];
    $idValidacao = $data['id_usuario'];
    $privado = $data['privado'];
    $denuncias = $data['denuncias'];
    if (strcmp($idValidacao, $_SESSION['id_usuario'])) { // Funciona, mas com a lógica inversa
        header("Location:../usuario/perfil.php");
    }
    Banco::desconectar();
}


?>





<main class="container">
    <h3>Atualize a postagem</h3>
    <div class="divider"></div>

    <?php
    echo '<form action="editar_postagem.php?id_postagem=' . $id_postagem . '" method="post">'; ?>

    <h3>Título:</h3>
    <div class="row">
        <div class="input-field col s12">
            <input class="validate" id="inputTitulo" name="titulo" type="text" placeholder="Escreva aqui o título do post" required oninvalid="this.setCustomValidity('Por favor insira o título!')" oninput="this.setCustomValidity('')" value="<?php echo !empty($titulo) ? $titulo : ''; ?>">
            <label class="active" for="inputTitulo">Título:</label>
        </div>
    </div>
    <h3>Descrição:</h3>
    <div class="row">
        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <textarea name="descricao" id="textareadescricao" class="materialize-textarea" placeholder="Descrição do post"><?php $linhas = explode("\r\n", $descricao);
                                                                                                                                    foreach ($linhas as $linha) {
                                                                                                                                        echo $linha;
                                                                                                                                    } ?>
          </textarea>
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
                    <textarea id="textareaConteudo" class="materialize-textarea" name="conteudo" placeholder="Escreva aqui o conteúdo do post">
          <?php $linhas = explode("\r\n", $conteudo);
            foreach ($linhas as $linha) {
                echo $linha;
            } ?>
          </textarea>
                    <label class="active" for="textareaConteudo">Conteúdo:</label>
                </div>
            </div>
        </div>
    </div>


    <h4>Tags:</h4>
    <div class="row">
        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <textarea id="textareaTags" class="materialize-textarea" name="tags" placeholder="Digite tags para facilitar que sua postagem seja encontrada! Exemplo: #tag">
          <?php if (!empty($tags)) {
                $linhas = explode("\r\n", $tags);
                foreach ($linhas as $linha) {
                    echo $linha;
                }
            } ?>
          </textarea>
                    <label class="active" for="textareaTags">Tags:</label>

                    <?php if ($denuncias < 5) { ?>

                        <p>
                            <label>
                                <input type="hidden" name="privado" value="0" id="publico">
                                <input type="checkbox" name="privado" value="1" id="privado" <?php if ($privado === '1') echo 'checked="checked"';
                                                                                                ?>>
                                <span>Tornar privada (atualmente está: <?php
                                                                        if ($privado == 1) {
                                                                            echo "<b>privada</b>";
                                                                        } else {
                                                                            echo "<b>pública</b>";
                                                                        } ?>)</span>
                            <?php }

                        if ($denuncias >= 5) {
                            echo "<blockquote>Sua postagem atingiu o limite de denúncias, ela não poderá mais ser aberta para o público. <br> Delete-a se desejar.</blockquote> 
  ";
                        }

                        if ($usuario_livre == 0) {
                            echo "<blockquote>Você recebeu muitas denúncias e não pode mais abrir postagens para o público. </blockquote>";
                        }

                            ?>
                </div>
            </div>
        </div>
    </div>

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
    <br>
</main>

<?php include_once "../etc/footer.php"; ?>


</body>

</html>