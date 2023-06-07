<?php

class DataBase {

  const HOST = 'localhost';
  const NAME = 'testecatalogo';
  const USER = 'root';
  const PASS = '';
  private $table;
  private $conn;

  public function __construct($table = null)  {
    $this->table = $table;
    $this->setConnection();
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