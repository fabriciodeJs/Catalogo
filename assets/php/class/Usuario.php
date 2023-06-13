<?php
include_once('DataBase.php');

class Usuario{

  public function __construct()  {
  
  }

  public function logar($user, $password) {
    $bd = new DataBase();
    
    $query = "SELECT *
              FROM usuarios 
              WHERE usuario = ? AND senha_usuario = ?";
    
    $stmt_verificarLogin = $bd->prepare($query);
    $stmt_verificarLogin->bindValue(1, $user, PDO::PARAM_STR);
    $stmt_verificarLogin->bindValue(2, $password, PDO::PARAM_STR);
    $stmt_verificarLogin->execute();

    $usuario = $stmt_verificarLogin->fetch(PDO::FETCH_ASSOC);


    if(!$usuario) return false;
    
    return true;
    
  }
}