<?php
$hostName = '192.168.45.222';
$dataBase = 'teste';
$user = 'fabricio';
$senha = '123456';

try {
  $conn = new PDO("mysql:host=$hostName;dbName=". $dataBase, $user, $senha);
} catch (PDOException $error) {
  echo "Falha na conexão do banco: (" . $error->getMessage(). ") ";
}

?>