<?php
    session_start();
    if(!isset($_SESSION['email'])){
        header("Location:login.php");
     }
    
  print_r($_SESSION);

  
  $titulo = "";
  $conteudo = "";
  $descricao = "";
  $tags = "";

  
    ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Página Inicial</title>
</head>

<body>
        <div class="container">
          <div class="jumbotron">
            <div class="row">
                <h2>CRUD em PHP <span class="badge badge-secondary">Versão 1 </span></h2>
            </div>
          </div>
            <div class="row">
                <p>
                    <a href="cadastro.php" class="btn btn-success">Adicionar</a>
                </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Título</th>
                            <th scope="col">Conteúdo</th>
                            <th scope="col">Tags</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'banco.php';
                        $pdo = Banco::conectar();
                        $sql = 'SELECT * FROM postagem ORDER BY id_postagem DESC';

                        foreach($pdo->query($sql)as $row)
                        {
                            echo '<tr>';
			                      echo '<th scope="row">'. $row['id_postagem'] . '</th>';
                            echo '<td>'. $row['titulo'] . '</td>';
                            echo '<td>'. $row['conteudo'] . '</td>';
                            echo '<td>'. $row['tags'] . '</td>';
                            echo '<td>'. $row['descricao'] . '</td>';
                            echo '<td width=250>';
                            echo '<a class="btn btn-primary" href="postagem.php">Info</a>';
                           // echo '<a class="btn btn-primary" href="read.php?id='.$row['id_postagem'].'">Info</a>';
                            echo ' ';
                            echo '<a class="btn btn-warning" href="atualizar.php?id='.$row['id_postagem'].'">Atualizar</a>';
                            echo ' ';
                            echo '<a class="btn btn-danger" href="deletar.php?id='.$row['id_postagem'].'">Excluir</a>';
                            echo '</td>';
                            echo '</tr>';
                        }

                
                        Banco::desconectar();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
