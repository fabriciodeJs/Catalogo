<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/login.css">
  <title>Login</title>
</head>
<body>
  <form action="assets/php/valida.php"  method="post">
    <label for="user">Usuario</label>
    <input type="text" name="user" id="user">
    <label for="password">Senha</label>
    <input type="password" name="password" id="password">
    <input id="botao" type="submit" name="logar" value="Logar">
    <div id="error" class="error"></div>
  </form>

 
  <script src="assets/javaScript/login.js"></script>
</body>
</html>