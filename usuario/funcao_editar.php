<?php
session_start();
require '../etc/banco.php';

echo 'foi pro lugar errado de novo';

$id = $_SESSION["id_usuario"];
if (!empty($_GET['id'])) {
  $id = $_REQUEST['id'];
}

if (null == $id) {
  header("Location: ../etc/index.php");
}


if (!empty($_POST)) { // Caso o POST da página anterior não esteja vazio

  $nomeErro = null;
  $emailErro = null;
  $generoErro = null;
  $senhaErro = null;

  $nome = $_POST['nome'];
  $nomeSessao = $_SESSION['nome'];
  $email = $_POST['email'];
  $emailSessao = $_SESSION["email"];
  $genero = $_POST['genero'];
  $senha = $_POST['senha'];
  $senhaNova = $_POST['senhaNova'];
  $naoAtualizar = false;
  //Validação
  $validacao = true;
  if (empty($nome)) {
    $nomeErro = 'Por favor digite o nome!';
    $validacao = false;
  }

  if (empty($email)) {
    $emailErro = 'Por favor digite o email!';
    $validacao = false;
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailErro = 'Por favor digite um email válido!';
    $validacao = false;
  }

  if (empty($genero)) {
    $genero = 'Por favor digite o genero!';
    $validacao = false;
  }

  if (empty($senha)) {
    $senha = 'Por favor digite a senha!';
    $validacao = false;
  }

  if (strcmp($email, $emailSessao) != 0 && strcmp($nome, $nomeSessao) != 0) {
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM usuario where email like '". $email."'"; // To do: Select email, usuario from usuario blabla ver se existem e comparar com o que foi digitado
    $q = $pdo->prepare($sql);
    $q->execute(array($email));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $naoAtualizar = true;

    header("Location: ../usuario/editar_perfil.php#emailusuarioRepetidos");
    Banco::desconectar();

  }

      // Mesmo código abaixo é de cadastro.php pra verificar se o e-mail existe no banco
  else if ( strcmp($email, $emailSessao) !== 0 ) {
  //  echo "E-mail digitado é diferente do da sessão, retorna: ", strcmp($email, $emailSessao);
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM usuario where email like '". $email."'"; // To do: Select email, usuario from usuario blabla ver se existem e comparar com o que foi digitado
    $q = $pdo->prepare($sql);
    $q->execute(array($email));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $emailValidacao = $data['email'];

    if (strcmp($emailValidacao, $email) == 0) { // Funciona, mas com a lógica inversa // Funcionando
 // echo "E-mail existe no banco", strcmp($email, $emailSessao);
      $naoAtualizar = true;
    header("Location: ../usuario/editar_perfil.php#emailRepetido");

  }
  Banco::desconectar();

  }
 else if ( strcmp($nome, $nomeSessao) !== 0 ) {
//  echo "Usuário digitado é diferente do da sessão, retorna: ", strcmp($nome, $nomeSessao);
  $pdo = Banco::conectar();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM usuario where nome like '". $nome."'"; // To do: Select email, usuario from usuario blabla ver se existem e comparar com o que foi digitado
  $q = $pdo->prepare($sql);
  $q->execute(array($nome));
  $data = $q->fetch(PDO::FETCH_ASSOC);
  $nomeValidacao = $data['nome'];
    if (strcmp($nomeValidacao, $nome) == 0) { // Funciona, mas com a lógica inversa // Funcionando
 // echo "Nome existe no banco", strcmp($nome, $nomeSessao);

      $naoAtualizar = true;
   header("Location: ../usuario/editar_perfil.php#usuarioRepetido");
      
  }
  Banco::desconectar();

 }

  
// Código acima é de cadastro.php

  
  $pdo = Banco::conectar(); // Conecta ao banco e:
                            // verifica se a senha digitada na página anterior está correta;
                            // para isso, verifica se a senha existe (no banco) e se existe dentro do e-mail da sessão (que deve ser o mesmo e-mail do banco)
                            // retorna "1" caso existam ambos e-mail e senha no banco.
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql =  "SELECT COUNT(senha) AS num FROM usuario WHERE email LIKE '$emailSessao' AND senha LIKE '$senha'";
  $querySenha = $pdo->prepare($sql);
  $querySenha->execute([$bar]);
  $number_of_rows = $querySenha->fetchColumn();
  echo $senha;
  echo $emailSessao;
  echo $number_of_rows;
  // update data
  if ($naoAtualizar == false && $number_of_rows > 0 && (!empty($senhaNova))) {  // Verifica se a função anterior retornou n_o_r > 0 (senha correta) e se o campo de senha nova está preenchido
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE usuario set nome = ?, email = ?, senha = ?, genero = ? WHERE id_usuario = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($nome, $email, $senhaNova, $genero, $id));
    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;
    $_SESSION['genero'] = $genero;
    $_SESSION['id'] = $id;
    Banco::desconectar();
    header("Location: ../usuario/editar_perfil.php#senhaAlterada");

  } else if ($naoAtualizar == false && $number_of_rows > 0 && (empty($senhaNova))) { // Verifica se a primeira função retornou n_o_r > 0 (senha correta) e se o campo de senha nova está vazio
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE usuario set nome = ?, email = ?, genero = ? WHERE id_usuario = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($nome, $email, $genero, $id));
    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;
    $_SESSION['genero'] = $genero;
    $_SESSION['id'] = $id;
    Banco::desconectar();
    header("Location: ../usuario/perfil.php");
  }
  


 
} 


/*else { // Não sei o que isso faz
  $pdo = Banco::conectar();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM usuario where id_usuario = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($id));
  $data = $q->fetch(PDO::FETCH_ASSOC);
  $nome = $data['nome'];
  $email = $data['email'];
  $genero = $data['genero'];
  $senha = $data['senha'];
  Banco::desconectar();
}*/


    
/*   $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "SELECT * FROM usuario WHERE senha LIKE '$senha'";
        $q = $pdo->prepare($sql);
        $row = $q->fetch(PDO::FETCH_ASSOC);
        
        if ($row['num'] > 0) {


            */
?>
 <!--- Até agora isso não faz nada -->
<div class="modal fade" id="PopUpSenhaIncorreta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabe2" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabe2">ATENÇÃO!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p> O e-mail que você digitou já está em uso! </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
