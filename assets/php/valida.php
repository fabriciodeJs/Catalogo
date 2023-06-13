<?php
include('class/Produto.php');
include('class/Usuario.php');

if (!empty($_POST['nomeProduto']) 
  and !empty($_POST['codigoProduto']) 
  and !empty($_POST['descricaoProduto'])
  and !empty($_POST['valorProduto'])
  and !empty($_FILES['imagemProduto'])
  and !empty($_FILES['videoProduto'])) {

  $produto = new Produto();
  
  $produto->upload($_POST['nomeProduto'], $_POST['codigoProduto'], $_POST['descricaoProduto'], 
                   $_POST['valorProduto'], $_FILES['imagemProduto'], $_FILES['videoProduto']);
  echo 'cadastrar';
  
  die();
}
