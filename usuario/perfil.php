    <?php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location:../etc/login.php");
    }
    require '../etc/banco.php';
    //print_r($_SESSION);
    $id_usuario = $_SESSION['id_usuario'];
    $usuario_livre = $_SESSION['usuario_livre'];


    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * from usuario where id_usuario = ".$id_usuario;
    $q = $pdo->prepare($sql);
    $q->execute(array($id_usuario));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $denuncias = $data['denuncias'];
    Banco::desconectar();


    if ($denuncias == 10) {
        //echo 'igual 10<br>';
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE usuario set usuario_livre=0 where id_usuario = ".$id_usuario;
        $q = $pdo->prepare($sql);
        $q->execute(array($id_usuario));
        Banco::desconectar();
        $_SESSION['usuario_livre'] == 0;
    }

/*if ($usuario_livre == 1) {
    echo 'ta livre, vc tem '. $denuncias." denuncias<br>";
    echo "seu boolean é: " . $usuario_livre;
}
else if ($usuario_livre == 0) {
    echo 'ta preso, vc tem '. $denuncias." denuncias";
}*/

    ?>
   
        <?php include_once "../etc/header.php"; ?>


        <main class="container">
        <div class="section">
            <h3>Perfil</h3>
<div class="divider"></div>
            <div class="row">
                <p class="paragrafo"><b>Usuário: </b><?php echo $_SESSION['nome']; ?> </p>
            </div>
            <div class="row">

                <p class="paragrafo"><b>E-mail: </b><?php echo $_SESSION['email']; ?> </p>
            </div>
            <div class="row">

                <p class="paragrafo"><b>Gênero: </b><?php
                if ($_SESSION['genero'] == "M") {
                    echo "Masculino";
                }
                else if ($_SESSION['genero'] == "F") {
                    echo "Feminino";
                }
                else if ($_SESSION['genero'] == "NB") {
                    echo "Não-binário";
                }
                else if ($_SESSION['genero'] == "O") {
                    echo "Outro / Prefiro não declarar";
                }
                else {
                    echo "Gênero não definido!";
                }
                 ?> </p>
            </div>
            <div class="row">

                <?php $pdo = Banco::conectar();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "SELECT * FROM postagem where id_usuario =" . $id_usuario . " AND inativo = 0";
                $q = $pdo->prepare($sql);
                $q->execute();
                $data = $q->fetch(PDO::FETCH_ASSOC);
                $titulo = $data['titulo'] ?? "Sem postagens ainda!";
                $descricao = $data['descricao'] ?? "Sem postagens ainda!";
                $conteudo = $data['conteudo'] ?? "Sem postagens ainda!";
                $tags = $data['tags'] ?? "Sem postagens ainda!";
                $break = 25;
                ?>
</div>
<div class="divider"></div>
<div class="section">

                <h4>Seus posts:</h4>
                <table class="responsive-table highlight">
                    <thead>
                        <tr>
                            <th scope="col">Título</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Conteúdo</th>
                            <th scope="col">Tags</th>
                            <th scope="col">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pdo->query($sql) as $row) {
                            echo '<tr>';
                            // preg_replace possui uma expressão regex. / marca o início da expressão. ^ marca o início da captura.
                            // \s abrange whitespaces: espaços, \t, \n, \r, etc.
                            // {20} repete o \s 20 vezes, delimitando o tamanho da string até a quebra.
                            // ?= verifica se a captura (do \s) pode ser encontrada, ou seja, se há os caracteres.
                            // o segundo parâmetro de preg_replace, $1, está substituindo o resultado da primeira ($1) "captura" da expressão regex;
                            // ou seja, a do \s.
                            // <wbr> é "word break opportunity": quebra a string somente se precisar.

                            echo '<td>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['titulo']) . '</td>';
                            echo '<td>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['descricao']) . '</td>';
                            echo '<td>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['conteudo']) . '</td>';
                            echo '<td>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['tags']) . '</td>';
                            echo '<td>';
                            echo '<a class="btn-small #64b5f6 blue lighten-2" href="../postagem/postagem.php?id_postagem=' . $row['id_postagem'] . '">Informar<i class="large material-icons right">info_outline</i></a>';
                            echo '<a class="btn-small #ef9a9a red lighten-3" href="../postagem/editar_postagem.php?id_postagem=' . $row['id_postagem'] . '">Atualizar <i class="large material-icons right">edit</a> ';

                            echo '</td>';
                            echo '</tr>';
                        }
                        Banco::desconectar();
                        ?>

                    </tbody>
                </table>
                    </div>

                <br>

            </div>
            <div class="valign-wrapper row">
                <div class="col s12 m12 l12 center-align">
                    <a href="../postagem/criar_postagem.php" class="btn #9ccc65 light-green lighten-1">Criar nova postagem<i class="large material-icons right">add_box</i></a>
                
                    <a href="editar_perfil.php" class="btn #ef9a9a red lighten-3">Atualizar seu perfil<i class="large material-icons right">edit</i></a>
                    <?php echo ' '; ?>
                </div>
            </div>
            <div class="valign-wrapper row">

                <div class="col s12 m12 l12 center-align">
                        
                    <button data-target="modalDeletarPerfil" class="btn  btn-light btn-outline-secondary modal-trigger #d7ccc8 brown lighten-4" >Excluir seu perfil<i class="large material-icons right">delete_forever</i></a></button>
                </div>
            </div>

            <br>
            <div id="modalDeletarPerfil" class="modal">
    <div class="modal-content">
      <h4>Cuidado!</h4>
      <p>Tem certeza de que deseja <b>deletar o seu perfil</b>? Essa é uma ação <b>permanente</b> e não é reversível.</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
      <a href="deletar_perfil.php" class="modal-close waves-effect waves-green btn-flat #d7ccc8 brown lighten-4">Sim, desejo deletar o meu perfil.</a>
    </div>
  </div>
        </main>

        <?php include_once "../etc/footer.php"; ?>

