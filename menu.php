<?php 
  session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <span class="navbar-brand mb-0" aria-current="page"><?php if(isset($_SESSION["nome"])){echo $_SESSION["nome"];}else{echo "Visitante";}?></span>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tabelas
          </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="cadQuadrado.php">Quadrado <img src="img/square.svg" alt=""></a></li>
            <li><a class="dropdown-item" href="cadTabuleiro.php">Tabuleiro <img src="img/tabuleiro-de-xadrez.png"></a></li>
            <li><a class="dropdown-item" href="cadUsuario.php">Usuário <img src="img/user.svg" alt=""></a></li>
          </ul>
        </li>
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Login
          </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="login.php">Login <img src="img/log-in.svg" alt=""></a></li>
            <li><a class="dropdown-item" href="login.php?acao=logout">Logout <img src="img/log-out.svg" alt=""></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<br>
<script src="bootstrap/js/bootstrap.min.js" crossorigin="anonymous"></script>