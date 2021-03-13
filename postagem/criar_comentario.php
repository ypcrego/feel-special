<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../etc/login.php");
}

require '../etc/banco.php';
$id_postagem = $_REQUEST['id_postagem'];
$id_usuario = $_SESSION['id_usuario'];
$conteudoComentario = $_POST['conteudoComentario'];
$anonimo = $_POST['anonimo'];

    if(!empty($_POST["conteudoComentario"]))
    {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO comentario (id_usuario, id_postagem, conteudo, anonimo) VALUES(?,?,?,?)";
      //  $sql = "INSERT INTO postagem (id_usuario, titulo, conteudo, descricao, tags) VALUES(?,?,?,?,?)";

        $q = $pdo->prepare($sql);
        $q->execute(array($id_usuario, $id_postagem, $conteudoComentario, $anonimo));
        
        Banco::desconectar();
        header("Location: ../postagem/postagem.php?id_postagem=". $id_postagem);
    }
        else {
        header("Location: ../postagem/postagem.php?id_postagem=". $id_postagem);

        }