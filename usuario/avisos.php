<?php
session_start();
require '../etc/banco.php';
include_once "../etc/header.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location:../etc/login.php");
}

$usuario_livre = $_SESSION['usuario_livre'];

$pdo = Banco::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "select * from postagem where id_usuario = ".$_SESSION['id_usuario']. " and denuncias >= 5";
$q = $pdo->prepare($sql);
$q->execute(array());
$data = $q->fetch(PDO::FETCH_ASSOC);
//$titulo = $data['titulo'] ?? '';


?>
<main class="container">


    <div class="section"> 
        <h3 class="center">Avisos</h3>
<div class="divider"></div>

        <!-- Começo dos comentários novos -->
        <?php
        if ($usuario_livre == 0) { // caso a conta da pessoa esteja restrita
            echo '<div class="row">
        <div class="dropdown-trigger secondary-content" ><i class="material-icons">grade</i></div>
            <ul class="collection">
                <li class="collection-item avatar">
                    <i class="material-icons circle #e53935  red darken-1 ">sentiment_very_dissatisfied</i>
                    <p class="#e53935 red darken-1 grey-text text-lighten-2  center"><b>Sua conta foi restrita!</b></p>
                    <p>Infelizmente, outros usuários denunciaram suas postagens e/ou seus comentários até que eles atingissem o limite! <br>
                    Lamentamos informar que você não poderá: <br> 
                    <blockquote>   Deixar suas postagens públicas; <br> </blockquote>
                    <blockquote> Comentar nas postagens de outros usuários; <br> </blockquote>
                    <blockquote> Pesquisar as postagens de outros usuários, mesmo que elas estejam abertas para o público. <br> </blockquote>
                        Entretanto, você ainda pode: <br>
                        <blockquote> Escrever suas próprias postagens desde que elas sejam privadas; <br> </blockquote>
                        <blockquote> Alterar suas próprias postagens; <br> </blockquote>
                        <blockquote> Deletar suas próprias postagens. <br> </blockquote>
                    </p>
                </li>
                </ul>
        </div>';
        }
        
        if ($data != null) { //caso a pessoa tenha alguma postagem que recebeu a quantidade máxima de denúncias
        foreach($pdo->query($sql)as $row) {
        echo '<div class="row">
        <div  class="dropdown-trigger secondary-content"><i class="material-icons">grade</i></div>
            <ul class="collection">
                <li class="collection-item avatar">
                <i class="large material-icons circle #006064 yellow darken-3  ">sentiment_dissatisfied</i>
                    <p class="#ffeb3b yellow black-text text-darken-4  center"><b> Uma postagem sua atingiu o limite de denúncias!</b></p>
                    <p>Infelizmente, outros usuários da plataforma denunciaram a sua postagem <b>"' .$row['titulo']. '"</b>! <br>
                    Lamentamos informar que este post específico <b>não poderá mais ser disponibilizado para o público</b>. Entretanto, você ainda pode mantê-lo
                    para você de forma privada, ou excluí-lo se desejar.       
                    </p>
                </li>
                </ul>
        </div>';
            }
        } 
        
        if ($usuario_livre == 1 && $data == null) {
            echo'<div class="container"><blockquote>Você não possui avisos!</blockquote></div>';
        }

        ?>

        <!-- Dropdown Trigger -->
        <!-- <a class='dropdown-trigger btn' href='#' data-target='dropdown1'>Drop Me!</a> -->

        <!-- Dropdown Structure -->
        <ul id='dropdown1' class='dropdown-content'>
            <li><a href="#!">one</a></li>
            <li><a href="#!">two</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!">three</a></li>
            <li><a href="#!"><i class="material-icons">view_module</i>four</a></li>
            <li><a href="#!"><i class="material-icons">cloud</i>five</a></li>
        </ul>
    </div>
    <!-- Fim dos comentários novos -->
</main>

<?php include_once "../etc/footer.php"; ?>