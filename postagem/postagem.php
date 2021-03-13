<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../etc/login.php");
}

require '../etc/banco.php';
include_once "../etc/header.php";
// print_r($_SESSION);


?> <script>
    if (window.location.hash === '#denunciaSucesso') {
        chamarPopupDenunciaSucesso();
        console.log('fireddenuncia'); // só testando
    }
</script>

<?php
$id_postagem = $_REQUEST['id_postagem'];
$denunciarPostagemVisivel = false;
$denunciarComentarioVisivel = false;

?>

<main class="container">
    <?php $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM postagem where id_postagem = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id_postagem));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $titulo = $data['titulo'] ?? '';
    $descricao = $data['descricao'] ?? '';
    $conteudo = $data['conteudo'] ?? '';
    $tags = $data['tags'] ?? '';
    $idUsuarioPostagem = $data['id_usuario'] ?? '';
    $booleanPrivado = $data['privado'] ?? '';
    $booleanInativo = $data['inativo'] ?? '';
    $idUsuarioSessaoAtual = $_SESSION['id_usuario'] ?? '';
    $break = 50000;

    if ( $booleanInativo == 1 || $booleanPrivado == 1 && strcmp($idUsuarioPostagem, $_SESSION['id_usuario']) != 0) { // Funciona, mas com a lógica inversa
        header("Location: ../usuario/perfil.php");
    }
    //      if (strcmp($idUsuarioPostagem, $_SESSION['id_usuario'])) { // Funciona, mas com a lógica inversa
    //         header("Location: ../usuario/perfil.php");x  
    //       } // 
    Banco::desconectar();

    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlComentario = "SELECT * from usuario INNER JOIN comentario where comentario.id_usuario = usuario.id_usuario AND id_postagem =" . $id_postagem . " AND comentario.inativo = 0 ORDER BY id_comentario";
    //    $sqlComentario = "SELECT * FROM comentario where id_postagem =" . $id_postagem;

    // SELECT * FROM comentario where id_postagem =" . $id_postagem
    $q = $pdo->prepare($sqlComentario);
    $q->execute();
    $dataComentario = $q->fetch(PDO::FETCH_ASSOC);
    $conteudoComentario = $dataComentario['conteudo'] ?? ' ';
    $idUsuarioComentario = $dataComentario['id_usuario'] ?? ' ';
    $nomeUsuarioComentario = $dataComentario['nome'] ?? ' ';
    //   $booleanAnonimo = $data['anonimo'];
    Banco::desconectar();
    // SELECT usuario.nome FROM usuario,comentario WHERE usuario.id_usuario = comentario.id_usuario; 

    // SELECT nome FROM usuario where id_usuario = ?
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM usuario where id_usuario = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($idUsuarioComentario));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    // $nomeUsuarioComentario = $data['nome']; // Não pega o nome certo

    Banco::desconectar();

    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql =  "SELECT COUNT(id_usuario) AS num FROM denuncia_postagem WHERE id_usuario LIKE '$idUsuarioSessaoAtual' AND id_postagem LIKE '$id_postagem'";
    $query = $pdo->prepare($sql);
    $query->execute([$sql]);
    $number_of_rows = $query->fetchColumn();
    //echo $number_of_rows;
    if ($number_of_rows == 0) {
        //echo 'a';
        $denunciarPostagemVisivel = true;
    }
    Banco::desconectar();
    ?>


    <h3> Informações da postagem </h3>
    <div class="divider"></div>

    <div class="section">
        <p class="paragrafo"><b>Título: </b><?php echo $titulo; ?> </p>
        <?php if (!empty($descricao)) {
            echo '
        <div class="row">
        <p class="paragrafo"><b>Descrição: </b></p>
            <div class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <textarea readonly="readonly"id="textareadescricao" class="materialize-textarea">' . $descricao . '
          </textarea>
                    </div>
                </div>
            </div>
       </div>';
        }
        ?>

        <p class="paragrafo"><b>Conteúdo: </b></p>
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <textarea readonly="readonly" id="textareaConteudo" class="materialize-textarea">
          <?php $linhas = explode("\r\n", $conteudo);
            foreach ($linhas as $linha) {
                echo $linha;
            } ?>
          </textarea>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($tags)) {
            echo '<div class="row">
            <p class="paragrafo"><b>Tags: </b></p>
                <div class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea readonly="readonly" id="textareatags" class="materialize-textarea">';
            $linhas = explode("\r\n", $tags);
            foreach ($linhas as $linha) {
                echo $linha;
            }
            echo '
              </textarea>
                        </div>
                    </div>
                </div>
           </div>';
        }
        ?>
        <div class="row">
            <?php
            if ($idUsuarioPostagem == $idUsuarioSessaoAtual) {


                echo   ' <div class="col s12 m12"><a href="../postagem/editar_postagem.php?id_postagem=' . $id_postagem . '" class="btn btn-secondary #ef9a9a red lighten-3">Atualizar postagem<i class="large material-icons right">edit</i></a>';
                echo ' ';
                echo   '<button data-target="modalDeletarPostagem" class="btn modal-trigger #bdbdbd grey lighten-1
                ">Remover postagem<i class="large material-icons right">delete_forever</i></a>';
                echo '</div></div>';
            } else {
                echo ' </div> <div class="float-right">';
                if ($denunciarPostagemVisivel == true) {
                    echo   '<a href="../postagem/denunciar_postagem.php?id_postagem=' . $id_postagem . '" class="btn #e53935 red darken-1">Denunciar postagem<i class="large material-icons right">security</i></a>';
                } else {
                    echo   '<a class="btn #e57373 red lighten-2" disabled">Você já denunciou essa postagem!</a>';
                }
                /*  echo '<form action="../postagem/denunciar_postagem.php?id_postagem='. $id_postagem;
               echo '<button type="submit" class="btn   btn-outline-danger float-right">Denunciar</button> */
                echo '</div>';
            }
            ?>

            <!---  </div> --->

            <br>
            <br>
            <?php
            echo '<form action="../postagem/criar_comentario.php?id_postagem=' . $id_postagem . '" method="post">' ?>
        </div>
        <div class="divider"></div>
        <div class="section">
            <div class="row">
                <div class="col s12 m12">
                    <div class="row">
                        <h5>Comentar:</h5>
                        <div class="input-field col s12">
                            <textarea id="textareaComentario" class="materialize-textarea" name="conteudoComentario"></textarea>
                            <label for="textareaComentario">Faça um comentário!</label>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <p>
                        <label>
                            <input type="hidden" name="anonimo" value="0" id="default">
                            <input type="checkbox" name="anonimo" value="1" id="anonimosim" />
                            <span>Comentar como anônimo</span>
                        </label>
                </div>
            </div>
            <div class="row right">
                <div class="col s12 m12">
                    <button class="btn btn-secondary #9ccc65 light-green lighten-1">Enviar<i class="large material-icons right">send</i></button>
                </div>
            </div>
            </form>

            <br>

            <h4>Comentários:</h4>
            <hr />
            <!-- Faz um select no banco das denúncias do comentário. Isso é para mostrar ao usuário que ele já denunciou um comentário. -->
            <?php foreach ($pdo->query($sqlComentario) as $row) {
                $pdo = Banco::conectar();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql =  "SELECT COUNT(id_usuario) AS num FROM denuncia_comentario WHERE id_usuario LIKE '$idUsuarioSessaoAtual' AND id_comentario LIKE '$row[id_comentario]'";
                $query = $pdo->prepare($sql);
                $query->execute([$sql]);
                $number_of_rows = $query->fetchColumn();
                //echo $number_of_rows;
                if ($number_of_rows == 0) {
                    //echo 'a';
                    $denunciarComentarioVisivel = true;
                } else {
                    $denunciarComentarioVisivel = false;
                }
                Banco::desconectar();
            // Esse foreach é fechado apenas no final do código. //

            // Echo no botão de deletar comentário com o id da postagem, do comentário e do id do usuário que fez o comentário.
                echo '<form action="../postagem/deletar_comentario.php?id_postagem='
                    . $id_postagem . '&id_comentario=' . $row['id_comentario'] . '&id_usuario_comentario=' . $row['id_usuario'] . '
            " method="post">';
            ?>

                <div class="novos-comentarios">
                    <!-- Começo dos comentários novos -->
                    <div class="row">
                        <a class="dropdown-trigger secondary-content" data-target="dropdown1"><i class="material-icons">grade</i></a>
                        <ul class="collection">
                            <li class="collection-item avatar">
                                <i class="#c5cae9 indigo lighten-4 material-icons circle">person</i>

                                <!-- php pro nome de quem comentou -->
                                <?php if ($row['anonimo'] == 1) {
                                    echo '<span class="title"><b>Anônimo comentou: </b></span>';
                                } else if ($row['anonimo'] == 0) {
                                    echo '<span class="title"><b>"' . $row['nome'] . '" comentou:</b></span>';
                                }
                                ?>

                                <!-- php pro conteúdo do comentário -->
                                <?php echo '
                                    <p>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['conteudo']) . ' </p>';
                                ?>

                            </li>
                    </div>

                    <!-- Dropdown Trigger -->
                    <!-- <a class="dropdown-trigger btn" href="#" data-target="dropdown1">Drop Me!</a> -->
                    <!-- Dropdown Structure -->


                    <!--coisa do dropdown-->
                    <!--  <ul id='dropdown1' class='dropdown-content'> -->
                    <?php
                    if ($idUsuarioPostagem == $idUsuarioSessaoAtual || $row['id_usuario'] == $idUsuarioSessaoAtual) {
                        echo '<button class="btn btn-link #bdbdbd grey lighten-1">Remover Comentário <i class="large material-icons right">delete_forever</i></button>';
                    } else {
                        if ($denunciarComentarioVisivel) {
                            echo '<a class="btn #e53935 red darken-1" href="../postagem/denunciar_comentario.php?id_comentario=' .
                                $row['id_comentario'] . '&id_postagem=' .  $id_postagem . ' ">Denunciar comentário<i class="large material-icons right">security</i></a>';
                        } else {
                            echo '<btn class="btn #e57373 red lighten-2">Você já denunciou esse comentário!</a><br>';
                        }
                    }
                    ?>
                    <!-- </ul> -->
                </div>
        </div>
        </form>
    <?php } ?>
    </div>
    <div id="modalDenunciaSucesso" class="modal">
        <div class="modal-content">
            <h4>SUCESSO!</h4>
            <p>Denúncia realizada. Agradecemos pela colaboração!</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Ok</a>
        </div>
    </div>

    <div id="modalDeletarPostagem" class="modal">
        <div class="modal-content">
            <h4>Cuidado!</h4>
            <p>Tem certeza de que deseja <b>deletar a sua postagem</b>? Essa é uma ação <b>permanente</b> e não é reversível.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <?php echo '<a href="../postagem/deletar_postagem.php?id_postagem=' . $id_postagem . '"   class="modal-close waves-effect waves-green btn #bdbdbd grey lighten-1">Sim, desejo deletar a minha postagem.</a>';
            ?>
        </div>
    </div>
</main>

<?php include_once "../etc/footer.php"; ?>