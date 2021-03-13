<?php require "../etc/head.php"; ?>
<!--- SEMPRE COLOCAR ISSO PRIMEIRO -->

<body>
  <header>


    <?php
    /* Quando o usuário não fez login */
    if (!isset($_SESSION['email'])) {
      /* Nav normal em telas grandes*/
      ?>
      <nav class="col-s12">
    <div class="nav-wrapper">
    <a href="../etc/index.php" id="feelSpecial" class="brand-logo center flow-text">Feel Special</a>
    <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    <ul class="left hide-on-med-and-down">
    <li class="no-padding">
      <li><a href="../etc/index.php">Início</a></li>
        <li><a href="../etc/login.php">Faça login</a></li>
        <li><a href="../usuario/cadastro.php">Cadastre-se</a></li>
        <li><a href="../etc/cvv.php">CVV</a></li>
        </li>
      </ul>
    </div>
  </nav>
      <!-- Nav em sidebar em telas menores -->

  <ul id="slide-out" class="sidenav">
  <li><a href="../etc/index.php">Início</a></li>
  <li><a href="../etc/login.php">Faça login</a></li>
  <li><a href="../usuario/cadastro.php">Cadastre-se</a></li>
  <li><a href="../etc/cvv.php">CVV</a></li>
  </ul>
  
  </header>
  <?php
    }
    /* Quando o usuário fez login */
    if (isset($_SESSION['email'])) {
      $usuario_livre = $_SESSION['usuario_livre'];
      /* Nav normal em telas grandes*/
    ?>

      <nav class="col-s12">
        <div class="nav-wrapper">
          <a href="../etc/index.php" id="feelSpecial" class="brand-logo center flow-text">Feel Special</a>
          <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
          <ul class="left hide-on-med-and-down">
            <li><a href="../etc/index.php">Início</a></li>
            <li><a href="../usuario/perfil.php">Perfil</a></li>
            <li><a href="../usuario/avisos.php">Avisos</a></li>
            <li><a href="../etc/cvv.php">CVV</a></li>
            <li><a href="../etc/logout.php">Faça logout</a></li>
          </ul>
          <!--- Nav em sidebar em telas menores -->
          <ul id="slide-out" class="sidenav">
            <li><a href="../etc/index.php">Início</a></li>
            <li><a href="../usuario/perfil.php">Perfil</a></li>
            <li><a href="../usuario/avisos.php">Avisos</a></li>
            <li><a href="../etc/cvv.php">CVV</a></li>
            <li>
              <form action="../postagem/pesquisar.php" method="post">
                <input name="pesquisa" type="search" placeholder="Pesquise uma postagem!" aria-label="Search" />
                <div class="s5 m8 l1 offset-s7 offset-l2">
                  <button class="btn btn-outline-success #ff4081 pink accent-2" type="submit">Pesquisar</button>
              </form>
            </li>
            <li><a href="../etc/logout.php">Faça logout</a></li>

          </ul>

          <div class="row right hide-on-small-only">

            <div class="col s5 m8 l11 offset-s7">
              <form action="../postagem/pesquisar.php" method="post">
                <input name="pesquisa" class="placeholderpesquisar" type="search" placeholder="Pesquise uma postagem!" aria-label="Search" />
                <div class="col s5 m8 l1 offset-s7 offset-l2">

                  <button class="btn btn-outline-success #ff4081 pink accent-2" type="submit">Pesquisar
                  </button>
                </div>
              </form>
            </div> <!-- fecha a div do col s5 m8 l11 offset-s7 -->
          </div> <!-- fecha a div do row right hide-on-smal-only -->
        </div> <!-- fecha a div do nav-wraper -->
      </nav>
  </header>
<?php } ?>