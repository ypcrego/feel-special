<?php


// session_start inicia a sessão
session_start();

define ('HOST', '127.0.0.1');
define ('USUARIO', 'root');
define ('SENHA', 'root');
define ('DB', 'tcc');

$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die ('Não foi possível conectar'); 


// as variáveis login e senha recebem os dados digitados na página anterior

// as próximas 3 linhas são responsáveis em se conectar com o bando de dados.

$email = mysqli_real_escape_string($conexao, $_POST['email']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);
$query = "SELECT * FROM usuario WHERE email LIKE '$email' and senha LIKE '$senha' AND inativo = 0";

// A variavel $result pega as varias $login e $senha, faz uma 
//pesquisa na tabela de usuarios
$result = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($result);
/* Logo abaixo temos um bloco com if e else, verificando se a variável $result foi 
bem sucedida, ou seja se ela estiver encontrado algum registro idêntico o seu valor
será igual a 1, se não, se não tiver registros seu valor será 0. Dependendo do 
resultado ele redirecionará para a página site.php ou retornara  para a página 
do formulário inicial para que se possa tentar novamente realizar o login 
*/


if(mysqli_num_rows ($result) > 0 )
{
$_SESSION['email'] = $email;
$_SESSION['senha'] = $senha;

$id_usuario = $row["id_usuario"];
$_SESSION['id_usuario'] = $id_usuario;

$nome = $row["nome"];
$_SESSION['nome'] = $nome;

$genero = $row["genero"];
$_SESSION['genero'] = $genero;

$usuario_livre = $row["usuario_livre"];
$_SESSION['usuario_livre'] = $usuario_livre;

header('Location: ../usuario/perfil.php');
exit();
}
else{
  unset ($_SESSION['email']);
  unset ($_SESSION['senha']);
  header('Location: ../etc/login.php');

}



 
?>