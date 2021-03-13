<?php
session_start();
require '../etc/banco.php';

$id_postagem = $_REQUEST['id_postagem'];
$id_comentario = $_REQUEST['id_comentario'];
$id_usuario_comentario = $_REQUEST['id_usuario_comentario'];



//select para pegar o id_usuario que está logado e comparar com o id_usuário da postagem
$pdo = Banco::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT id_usuario FROM postagem where id_postagem = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id_postagem));
$data = $q->fetch(PDO::FETCH_ASSOC);
$idUsuarioPostagem = $data['id_usuario'];
$idUsuarioSessaoAtual = $_SESSION['id_usuario'];

//select para pegar o id_usuario do comentário para conseguir apagar o seu próprio comentário
//o comentário não será mais visível para nenhum usuário após apagado. entretanto, será mantido no banco (ficará inativo = 1).
$pdo = Banco::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT id_usuario FROM comentario where id_usuario = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id_postagem));
$data = $q->fetch(PDO::FETCH_ASSOC);
$idUsuarioComentario = $data['id_usuario'];

 //  || $idUsuarioComentario == $idUsuarioComentario
if ($idUsuarioPostagem == $idUsuarioSessaoAtual || $id_usuario_comentario == $idUsuarioSessaoAtual) {
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE comentario SET inativo = 1 where id_comentario = " . $id_comentario . " AND id_postagem = " . $id_postagem;
    $q = $pdo->prepare($sql);
    $q->execute(array($id_postagem, $id_comentario));
    Banco::desconectar();
    header("Location: ../postagem/postagem.php?id_postagem=" . $id_postagem);
}

else {
    header("Location: ../postagem/postagem.php?id_postagem=" . $id_postagem);

}