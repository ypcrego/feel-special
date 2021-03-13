<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../etc/login.php");
}

require '../etc/banco.php';

include_once "../etc/header.php";

$usuario_livre = $_SESSION['usuario_livre'];



?>
<main>
<div class="section">
            <h3>Resultados da busca por postagens</h3>
<div class="divider"></div><br/>
</div>
<?php
    $existemPostagens = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $usuario_livre == 1) {
    $variavelPesquisada = $_POST['pesquisa'];
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select * from postagem where (privado = 0 and inativo = 0) and (titulo like '%" .$variavelPesquisada. "%' or descricao like '%" .$variavelPesquisada. "%' or conteudo like '%" .$variavelPesquisada. "%' or tags like '%" .$variavelPesquisada. "%');";
    $q = $pdo->prepare($sql);
    $q->execute(array());
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $titulo = $data['titulo'] ?? '';
    $descricao = $data['descricao'] ?? '';
    $conteudo = $data['conteudo'] ?? '';
    $tags = $data['tags'] ?? '';
    if (empty($titulo) && empty($descricao) && empty($conteudo) && empty($tags)) {
        $existemPostagens = false;
        echo '<br><br><div class="container"><p><blockquote>Não há nenhuma postagem pública com os critérios pesquisados! Tente realizar outra consulta.</blockquote></p></div>';
    }   
    else {
        $existemPostagens = true;
    }
    $break = 25;
 
}
else if ($usuario_livre == 0) {
    echo '<br><br><div class="container"><blockquote>Você recebeu muitas denúncias e não pode mais pesquisar postagens.</blockquote></div><br>';
}
else {
    echo '<p>Oops! Ocorreu uma falha. Tente novamente!';
}
?>

       
      <?php  
      if ($existemPostagens == true) {
          echo ' <div class="container">

          <table class="table">
             <thead>
                 <tr>
                     <th scope="col">Título</th>
                     <th scope="col">Descrição</th>
                     <th scope="col">Conteúdo</th>
                     <th scope="col">Tags</th>
                     <th scope="col">Ação</th>
                 </tr>
             </thead>
             <tbody>';
      foreach($pdo->query($sql)as $row)
      if (!empty($row)) {

                        {
                            echo '<tr>';
                            // preg_replace possui uma expressão regex. / marca o início da expressão. ^ marca o início da captura.
                            // \s compreende whitespaces: espaços, \t, \n, \r, etc.
                            // {20} repete o \s 20 vezes, delimitando o tamanho da string até a quebra.
                            // ?= verifica se a captura (do \s) pode ser encontrada, ou seja, se há os caracteres.
                            // o segundo parâmetro de preg_replace, $1, está substituindo o resultado da primeira ($1) "captura" da expressão regex
                            // ou seja, a do \s.
                            // <wbr> é "word break opportunity": quebra a string somente se precisar.
                            echo '<td>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['titulo']) . '</td>';
                            echo '<td>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['descricao']) . '</td>';
                            echo '<td>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['conteudo']) . '</td>';
                            echo '<td>' . preg_replace('/([^\s]{20})(?=[^\s])/', '$1'.'<wbr>', $row['tags']) . '</td>';
                            echo'<td>';
                            echo '<a class="btn btn-light btn-outline-secondary #64b5f6 blue lighten-2" href="../postagem/postagem.php?id_postagem='.$row['id_postagem'].'">Informar</a>';

                            echo ' ';
                            echo '<br>';
                            
                            echo ' ';
                            echo '<br>';

                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                }
                        Banco::desconectar();
                        ?>

                    </tbody>   </table> </div>
                    <br> 
                </main>

<?php
include_once "../etc/footer.php";
?>