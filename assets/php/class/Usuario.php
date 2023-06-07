<?php
include_once('DataBase.php');

class Usuario{

  public function __construct()  {
  
  }

  public function logar($user, $password) {
    $bd = new DataBase();
    $query = "SELECT id_usuario, usuario, senha_usuario
              FROM usuarios 
              WHERE usuario = ? AND senha_usuario = ?";
    
    $stmt_verificarLogin = $bd->prepare($query);
    $stmt_verificarLogin->bindValue(1, $user, PDO::PARAM_STR);
    $stmt_verificarLogin->bindValue(2, $password, PDO::PARAM_STR);
    $stmt_verificarLogin->execute();

    if (!$usuario = $stmt_verificarLogin->fetch(PDO::FETCH_ASSOC)) 
    die('Usuario não Localizado!');
      
    header('location: ../../cadastro.php');
    
  }


  // public function cadastra($user, $password){
  //   $bd = new DataBase();
  //   $query = "INSERT INTO testecatalogo
  //             FROM usuarios 
  //             VALUES(?,?);

    
  // }


}