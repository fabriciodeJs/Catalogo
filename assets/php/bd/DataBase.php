<?php

class DataBase {

  /**
  * Host de conexão com banco de dados
  * @var string
  */
  const HOST = '192.168.45.222';

  /**
  *Nome do banco de dados
  * @var string
  */
  const NAME = 'teste';

  /**
  * usuário do banco de dados
  * @var string
  */
  const USER = 'fabricio';

  /**
  * Senha do banco de dados
  * @var string
  */
  const PASS = '123456';

  /**
  * tabela banco de dados
  * @var string
  */
  private $table;

  /**
  * tabela banco de dados
  * @var PDO
  */
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

  public function uploadDescricao($codigo, $nome, $descricao, $valor) {
    $query_produto = "INSERT INTO produto(CODIGO, NOME, DESCRICAO, VALOR) VALUES (?, ?, ?, ?)";
    $stmt_envioProduto = $this->conn->prepare($query_produto);
    $stmt_envioProduto->bindValue(1, $codigo, PDO::PARAM_STR);
    $stmt_envioProduto->bindValue(2, $nome, PDO::PARAM_STR);
    $stmt_envioProduto->bindValue(3, $descricao, PDO::PARAM_STR);
    $stmt_envioProduto->bindValue(4, $valor, PDO::PARAM_STR);
    if (!$stmt_envioProduto->execute()) {
      throw new Exception("Ao fazer Upload dos dados!");
    }

    return true;
  }

  public function uploadArquivos($imagens, $video) {

  }

  public function validateProduct($codigo) {
    $query_verificarCodigo = "SELECT COUNT(*) FROM produto WHERE CODIGO = ?";
    $stmt_verificarCodigo = $this->conn->prepare($query_verificarCodigo);
    $stmt_verificarCodigo->bindValue(1, $codigo, PDO::PARAM_STR);
    $stmt_verificarCodigo->execute();
    if ($count = $stmt_verificarCodigo->fetchColumn() > 0) {
      throw new Exception("O código do produto já existe no banco de dados.");
    }

    return true;
  }






}