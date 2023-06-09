<?php

class DataBase {

  const HOST = 'localhost';
  const NAME = 'testecatalogo';
  const USER = 'root';
  const PASS = '';
  private $conn;

  public function __construct()  {
    $this->setConnection();
  }

  public function lastInsertId(){
    try {
      return $this->conn->lastInsertId();
    } catch (PDOException $error) {
      echo 'Error: ' . $error;
    }
    
  }

  public function beginTransaction(){
    return $this->conn->beginTransaction();
  }

  public function commit(){
   return $this->conn->commit();
  }

  public function rollBack(){
    return $this->conn->rollBack();
  }

  private function setConnection() {
    try {
      $this->conn = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $error) {
      die('Error:' . $error->getMessage());
    }
  }

  public function prepare($query) {
    try {
     $result = $this->conn->prepare($query);
     return $result;
    } catch (PDOException $error) {
      die('Error: ' . $error);
    }
 
}

}