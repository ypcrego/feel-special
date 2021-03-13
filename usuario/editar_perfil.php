<?php
session_start();
require '../etc/banco.php';
include_once "../etc/header.php";

?> <script>
  if (window.location.hash === '#senhaAlterada') { // Verifica se o url tem #senhaAlterada
    chamarPopupLogout();
    console.log('fired2'); // só testando




  } else if (window.location.hash === '#emailRepetido') { // Verifica se o url tem #emailRepetido
    chamarPopupEmailRepetido();
    console.log('fired3'); // só testando
    // meu discord fechou e eu falando sozinho :clown::clown::clown::clown: voltando. 🤡

    //rindo com respeito de clown pra clown


  } else if (window.location.hash === '#usuarioRepetido') { // Verifica se o url tem #usuarioRepetido
    chamarPopupUsuarioRepetido();
    console.log('fired4'); // só testando




  } else if (window.location.hash === '#emailusuarioRepetidos') { // Verifica se o url tem #emailusuarioRepetidos
    //   chamarPopupEmailUsuarioRepetidos();
    chamarPopupUsuarioRepetido();
    chamarPopupEmailRepetido();
    console.log('fired5'); // só testando


  }
</script>

<?php
$id = $_SESSION["id_usuario"];
if (!empty($_GET['id'])) {
  $id = $_REQUEST['id'];
}

if (null == $id) {
  header("Location: ../etc/index.php");
}

if (!empty($_POST)) {


  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $genero = $_POST['genero'];
  $senha = $_POST['senha'];

} else {
  $pdo = Banco::conectar();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM usuario where id_usuario = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($id));
  $data = $q->fetch(PDO::FETCH_ASSOC);
  $nome = $data['nome'] ?? '';
  $email = $data['email'] ?? '';
  $genero = $data['genero'] ?? '';
  $senha = $data['senha'] ?? '';
  
  Banco::desconectar();
}
?>


<main class="container">
  <h3>Atualize o perfil</h3>
  <div class="divider"></div>

  <br>
  <form action="funcao_editar.php" method="post">
    <div>
      <div class="row">
        <div class="col-6 <?php echo !empty($nomeErro) ? 'error ' : ''; ?>">
          <label>Usuário: </label>
          <input type="text" name="nome" id="usuario" maxlength="30" placeholder="Digite um usuário" required oninvalid="this.setCustomValidity('Por favor insira o usuário!')" oninput="this.setCustomValidity('')" value="<?php echo !empty($nome) ? $nome : ''; ?>" required>

          <?php if (!empty($nomeErro)) : ?>
            <span><?php echo $nomeErro; ?></span>
          <?php endif; ?>
        </div> <!-- fecha div de usuário -->

        <div class="col-6 <?php echo !empty($emailErro) ? 'error ' : ''; ?>">
          <label>E-mail: </label>
          <input type="email" name="email" id="email" placeholder="Digite um e-mail" required oninvalid="this.setCustomValidity('Por favor insira o e-mail!')" oninput="this.setCustomValidity('')" value="<?php echo !empty($email) ? $email : ''; ?>" required>

          <?php if (!empty($emailErro)) : ?>
            <span><?php echo $emailErro; ?></span>
          <?php endif; ?>
        </div> <!-- fecha div de e-mail -->

      </div> <!-- fecha div de row -->


      <div>
        <div <?php echo !empty($generoErro) ? 'error ' : ''; ?>>
          <!--- COLOCAR PRA exibir o erro correto --->
          <div>
            <label for="genero">Gênero:</label>
          </div> <!-- fecha div da label gênero -->
          <div>
            <div>
              <p>
                <label>

                  <input type="radio" name="genero" id="masculino" value="M" <?php echo (isset($genero)  &&  $genero == "M") ? "checked" : null; ?> /> <span class="black-text">Masculino</span>
                </label>

              </p>
            </div> <!-- fecha div do gênero "M" -->
            <div>
              <p>
                <label>
                  <input type="radio" name="genero" id="feminino" value="F" <?php echo (isset($genero)  && $genero == "F") ? "checked" : null; ?> /> <span class="black-text">Feminino</span>
                </label>

              </p>
            </div> <!-- fecha div do gênero "F" -->
            <div>
            <p>
                <label>
                  <input type="radio" name="genero" id="nbinario" value="NB" <?php echo (isset($genero)  &&  $genero == "NB") ? "checked" : null; ?> /><span class="black-text">Não-binário</span>
                </label>
              </p>
            </div> <!-- fecha div do gênero "NB" -->
            <div>
              <p>
                <label>
                  <input type="radio" name="genero" id="outro" value="O" <?php echo (isset($genero)  &&  $genero == "O") ? "checked" : null; ?> /><span class="black-text">Outro / Não declarado</span>
                </label>
              </p>


            </div> <!-- fecha div de gênero "O" -->
            <?php if (!empty($generoErro)) : ?>
              <span><?php echo $generoErro; ?></span>
            <?php endif; ?>
          </div> <!-- fecha div de controls -->
        </div> <!-- fecha div de control-group -->
      </div> <!-- fecha div de centralizar -->

      <br>



      <div>
        <div>
          <label>Senha nova: </label>
        </div>
      </div>

      <div>
        <div class="col-5">
          <input type="password" name="senhaNova" id="senhaNova" placeholder="Preencha esse campo caso queira alterar a senha.">
        </div>
      </div>

      <br>

      <div>


        <div>
          <label>Senha atual: </label>
        </div>
      </div>
      <div>
        <div class="col-5">
          <input type="password" name="senha" id="senha" placeholder="Digite a senha atual para confirmar as alterações feitas." required oninvalid="this.setCustomValidity('Por favor insira a senha!')" oninput="this.setCustomValidity('')" value="" required>
        </div>
      </div>

      <span></span>

    </div>
    <br />
    <br />

    <div class="row valign-wrapper">
      <div class="col s8">
        <a href="perfil.php" class="btn waves-effect waves-light #9ccc65 light-green lighten-1">Cancelar</a>
      </div>
      <div class="col s3">
        <button class="btn waves-effect waves-light #9ccc65 light-green lighten-1" type="submit" name="action" value="any_value">Atualizar
          <i class="material-icons right">send</i>
        </button>
      </div> <!-- fecha div de centralizar o row -->
    </div> <!-- fecha div principal -->
    <br>
  </form>
  <br>
  <div id="modalLoginNovamente" class="modal">
    <div class="modal-content">
      <h4>ATENÇÃO!</h4>
      <p>Senha redefinida <b>com sucesso</b>! Você precisará fazer login novamente.</p>
    </div>
    <div class="modal-footer">
      <a class="modal-close waves-effect waves-green btn-flat">Ok</a>
    </div>
  </div>

  <div id="modalUsuarioEmUso" class="modal">
    <div class="modal-content">
      <h4>ATENÇÃO!</h4>
      <p>O usuário que você digitou já está em uso!</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Ok</a>
    </div>
  </div>

  <div id="modalEmailEmUso" class="modal">
    <div class="modal-content">
      <h4>ATENÇÃO!</h4>
      <p>O e-mail que você digitou já está em uso!</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Ok</a>
    </div>
  </div>

  <div id="modalEmailUsuarioEmUso" class="modal">
    <div class="modal-content">
      <h4>ATENÇÃO!</h4>
      <p>O e-mail e usuário que você digitou já estão em uso!</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Ok</a>
    </div>
  </div>


</main>

<?php include_once "../etc/footer.php"; ?>