<?php
require '../etc/banco.php';
include_once "../etc/header.php";
session_start();
if (isset($_SESSION['email'])) {
  header("Location: ../etc/index.php");
  exit();
}

if (!empty($_POST)) {
  //Acompanha os erros de validação
  $nomeErro = null;
  $emailErro = null;
  $senhaErro = null;
  $generoErro = null;

  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $genero = $_POST['genero'];

  //Validaçao dos campos:
  $validacao = true;
  if (empty($nome)) {
    $nomeErro = 'Por favor digite o seu nome!';
    $validacao = false;
  }


  if (empty($email)) {
    $emailErro = 'Por favor digite o endereço de email';
    $validacao = false;
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailError = 'Por favor digite um endereço de email válido!';
    $validacao = false;
  }

  if (empty($senha)) {
    $senhaErro = 'Por favor digite a senha!';
    $validacao = false;
  }

  if (empty($genero)) {
    $generoErro = 'Por favor digite o campo!';
    $validacao = false;
  }


  //  isso tava comentado 
  $pdo = Banco::conectar();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM usuario where email like '" . $email . "'"; // To do: Select email, usuario from usuario blabla ver se existem e comparar com o que foi digitado
  $q = $pdo->prepare($sql);
  $q->execute(array($email));
  $data = $q->fetch(PDO::FETCH_ASSOC);
  $emailValidacao = $data['email'];

  if (strcmp($emailValidacao, $email) == 0) { // Funciona, mas com a lógica inversa // Funcionando
    $validacao = false;
?>
    <script>
      chamarPopupEmailRepetido();
    </script>
  <?php
  }

  $pdo = Banco::conectar();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM usuario where nome like '" . $nome . "'"; // To do: Select nome, usuario from usuario blabla ver se existem e comparar com o que foi digitado
  $q = $pdo->prepare($sql);
  $q->execute(array($nome));
  $data = $q->fetch(PDO::FETCH_ASSOC);
  $nomeValidacao = $data['nome'] ?? '';

  if (strcmp($nomeValidacao, $nome) == 0) { // Funciona, mas com a lógica inversa // Funcionando
    $validacao = false;
  ?>

    <script>
      chamarPopupUsuarioRepetido();
    </script>

<?php
  }

  // isso estava comentado

  else  if ($validacao) {
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO usuario (nome, email, senha, genero) VALUES(?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($nome, $email, $senha, $genero));
    Banco::desconectar();
    header("Location: ../etc/login.php");
  }
}
?>

  <main class="container">
    <br>
    <h3>Cadastre-se</h3>
    <small id="dados-help" class="form-text text-muted">Nunca vamos compartilhar seus dados com ninguém.</small>
    <form action="cadastro.php" method="post">
      <div>
        <div  <?php echo !empty($nomeErro) ? 'error ' : ''; ?>>
          <label>Usuário: </label>
          <input type="text" name="nome" id="usuario" maxlength="30" placeholder="Digite um usuário" required oninvalid="this.setCustomValidity('Por favor insira o usuário!')" oninput="this.setCustomValidity('')" value="<?php echo !empty($nome) ? $nome : ''; ?>" required>

          <?php if (!empty($nomeErro)) : ?>
            <span class="help-inline"><?php echo $nomeErro; ?></span>
          <?php endif; ?>


        </div>
        <div <?php echo !empty($emailErro) ? 'error ' : ''; ?>>
          <label>E-mail: </label>
          <input type="email" name="email" id="email" placeholder="Digite um e-mail" required oninvalid="this.setCustomValidity('Por favor insira o e-mail!')" oninput="this.setCustomValidity('')" value="<?php echo !empty($email) ? $email : ''; ?>" required>

          <?php if (!empty($emailErro)) : ?>
            <span class="help-inline"><?php echo $emailErro; ?></span>
          <?php endif; ?>

        </div>
        <div <?php echo !empty($senhaErro) ? 'error ' : ''; ?>>
          <label>Senha: </label>
          <input type="password" name="senha" id="senha" placeholder="Digite uma senha" required oninvalid="this.setCustomValidity('Por favor insira a senha!')" oninput="this.setCustomValidity('')" value="<?php echo !empty($senha) ? $senha : ''; ?>" required>

          <?php if (!empty($senhaErro)) : ?>
            <span class="help-inline"><?php echo $senhaErro; ?></span>
          <?php endif; ?>

        </div>
<br>


        <div <?php echo !empty($generoErro) ? 'error ' : ''; ?>>
          <!--- COLOCAR PRA exibir o erro correto --->
          <label class="control-label">Gênero com que se identifica:</label>
          <div>
            <div>
              <p>
                <label>
                  <input class="with-gap" type="radio" name="genero" id="genero" value="M" <?php echo (isset($genero)  &&  $genero == "M") ? "checked" : null; ?> /> <span>Masculino</span>
                </label>
            </div>

            <div>
              <label>
                <input class="with-gap" type="radio" name="genero" id="genero" value="F" <?php echo (isset($genero)  && $genero == "F") ? "checked" : null; ?> required /> <span>Feminino</span>
              </label>

            </div>
            <div>
              <p>
                <label>
                  <input class="with-gap" type="radio" name="genero" id="genero" value="NB" <?php echo (isset($genero)  &&  $genero == "NB") ? "checked" : null; ?> /> <span>Não-binário</span>
                </label>

            </div>
            </p>
            <div>
              <p>
                <label>
                  <input class="with-gap" type="radio" name="genero" id="genero" value="O" <?php echo (isset($genero)  &&  $genero == "O") ? "checked" : null; ?> /> <span>Outro / Não declarado</span>
                </label>

            </div>
            </p>
            <?php if (!empty($generoErro)) : ?>
              <span class="help-inline"><?php echo $generoErro; ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div>
              <blockquote><p>Ao clicar em "Cadastrar", você estará automaticamente concordando com os <a href="../etc/termos.php">termos de uso e política de privacidade</a> de Feel Special!</p></blockquote>
      </div>


      <div class="row valign-wrapper">
        <div class="col s12 center">
          <button class="btn waves-effect waves-light  right #9ccc65 light-green lighten-1" type="submit" name="action" value="any_value">Cadastrar
            <i class="material-icons right">send</i>
          </button>
        </div>
      </div>
      <!-- Modal -->

    </form>
   

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

  </main>

  <?php include_once "../etc/footer.php"; ?>


</body>

</html>