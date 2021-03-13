<?php
session_start();
require '../etc/banco.php';


if (!isset($_SESSION['id_usuario'])) {
    header("Location:../etc/login.php");
}
$id_postagem = $_REQUEST['id_postagem'];
$id_usuario = $_SESSION["id_usuario"];
    
    echo $id_postagem;
    echo $id_usuario;

    //Update do banco:
    //a postagem não será mais visível para nenhum usuário após apagada. entretanto, será mantida no banco (ficará inativo = 1).
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE postagem set inativo = 1 where id_postagem = ".$id_postagem." AND id_usuario = " .$_SESSION['id_usuario'];
    $q = $pdo->prepare($sql);
    $q->execute(array($id_postagem));
    Banco::desconectar();
    header("Location: ../usuario/perfil.php");


    ?>
 