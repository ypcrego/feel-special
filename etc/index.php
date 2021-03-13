<?php
session_start();
include_once "../etc/header.php"; 
?>



    <main class="container"> 
  

<!--         <h3>Seja bem-vindo(a) ao seu diário digital, Dear Me!</h3> -->
<h3>Olá, este é o seu diário digital, Feel Special!</h3> 
        <br>
        <div class="row">
            <div class="col-6">
                <h5>O que é?</h5>
                <p class="paragrafo flow-text">Buscamos desenvolver um diário virtual cuja funcionalidade seja auxiliar você a registrar qualquer situação 
            de seu querer, seja um pensamento que lhe ocorreu ou o seu dia de forma geral, tendo a opção de compartilhar (ou não) suas postagens
            com outros usuários da plataforma.</p>
            <p  class="paragrafo flow-text"> Além disso, você pode interagir com outros usuários, ver suas postagens (utilizando a nossa
            ferramenta de pesquisas) e deixar seus próprios comentários e pensamentos (de forma respeitosa)!</p>
            </div>
            <div class="col-6">
                <h5>Como funciona?</h5>
             <!--   <p class="paragrafo">Dear Me funciona de forma objetiva, necessitando de poucos dados para realizar o cadastro.
            Apenas você pode ver, alterar e deletar suas postagens. Logo, é necessário estar logado.</p> -->
<p class="paragrafo flow-text">Feel Special funciona de forma objetiva, necessitando de poucos dados para realizar o cadastro.
            Você pode ver, alterar e deletar suas postagens. Como você vai perceber, Feel Special é uma plataforma
            intuitiva, explicativa e simples de se utilizar, e qualquer um pode aprender como manusear o website
            facilmente.</p> 
                <p class="paragrafo flow-text">É importante destacar que é necessário estar logado para ter a experiência completa.
                Para fazer o cadastro, só precisamos do seu e-mail, além de seu nickname e uma senha que irá lhe dar acesso ao
                    que você já postou.</p>
            </div>
            <div>
            <h6>Leia os <a href="../etc/termos.php">termos de uso e política de privacidade</a> de Feel Special!</h6>
           <?php if (!isset($_SESSION['email'])) {
        echo '
            <h6>Já tem uma conta? Faça <a href="../etc/login.php">login</a>!</h6>
            <h6>Caso não tenha uma conta, clique <a href="../usuario/cadastro.php">aqui</a> para criar uma.</h6>
            ';
           }
            ?>
            </div>
        </div>
    </main>
    <?php include_once "../etc/footer.php"; ?>