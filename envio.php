<?php
include_once('conexao.php');

class Produto {
  private $nomeProduto;
  private $descricaoProduto;
  private $codigoProduto; 
  private $valorProduto;
  private $imagemProduto = [];
  private $videoProduto;

  function __construct($nome, $descricao, $codigo, $valor, $imagens, $video)  {
    $this->nomeProduto = $nome;
    $this->descricaoProduto = $descricao;
    $this->codigoProduto = $codigo;
    $this->valorProduto = $valor;
    $this->imagemProduto = $imagens;
    $this->videoProduto = $video;

  }

  function getNomeProduto() {
    return $this->nomeProduto;
  }

  function getDescricaoProduto() {
    return $this->descricaoProduto;
  }

  function getCodigoProduto() {
    return $this->codigoProduto;
  }

  function getValorProduto() {
    return $this->valorProduto;
  }

  function getImagemProduto() {
    return $this->imagemProduto;
  }

  function getVideoProduto() {
    return $this->videoProduto;
  }




}



?>