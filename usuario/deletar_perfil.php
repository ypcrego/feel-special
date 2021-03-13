<?php
session_start();
require '../etc/banco.php';

$id = 0;

if (!isset($_SESSION['id_usuario'])) {
    header("Location:../etc/login.php");
}


if (!empty($_SESSION['id_usuario'])) {
    $id = $_SESSION['id_usuario'];


    //Update do banco:
    //o usuário não poderá mais entrar nessa conta. entretanto, ela será mantida no banco (ficará inativo = 1).
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE usuario set inativo = 1 where id_usuario = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    Banco::desconectar();
    session_unset();
    session_destroy();
    header("Location: ../etc/index.php");
}
?>

  