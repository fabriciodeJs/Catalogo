<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>
<body>
  <form action="assets/php/valida.php" method="post">
    <label for="user">Usuario</label>
    <input type="text" name="user" id="user">
    <label for="password">Senha</label>
    <input type="password" name="password" id="password">
    <input type="submit" name="logar" value="Logar">
  </form>
</body>
</html>