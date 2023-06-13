<?php

session_start();

$noUser = false;
include_once('assets/php/class/Usuario.php');

if (isset($_POST['user']) && isset($_POST['password'])) {
  $user = new Usuario();
  $result = $user->logar($_POST['user'], $_POST['password']);

  if ($result) {
    $_SESSION['cadastro'] = $_POST['user'];
    header("location: cadastro.php");
  
    exit();
  }
  
  $noUser = true;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/login.css">
  <title>Login</title>
</head>
<body>
  <form action=""  method="post">
    <label for="user">Usuario</label>
    <input type="text" name="user" id="user">
    <label for="password">Senha</label>
    <input type="password" name="password" id="password">
    <input id="botao" type="submit" name="logar" value="Logar">
    <div id="error" class="error">
      <?php if($noUser) : ?>
        <?= "<p>Usuario não localizado!</p>" ?>
      <?php endif ?>
    </div>
  </form>

  
  <script src="assets/javaScript/login.js"></script>
</body>
</html>