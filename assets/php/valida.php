<?php
include('class/Produto.php');

if (isset($_POST['nomeProduto']) 
  and isset($_POST['codigoProduto']) 
  and isset($_POST['descricaoProduto'])
  and isset($_POST['valorProduto'])
  and isset($_FILES['imagemProduto'])
  and isset($_FILES['videoProduto'])) {
  
  $nomeProduto = $_POST['nomeProduto'];
  $descricaoProduto = $_POST['descricaoProduto'];
  $codigoProduto = $_POST['codigoProduto'];
  $valorProduto = $_POST['valorProduto'];
  $imagemProduto = $_FILES['imagemProduto'];
  $videoProduto = $_FILES['videoProduto'];

  $produto = new Produto($nomeProduto, $descricaoProduto, $codigoProduto, $valorProduto, $imagemProduto, $videoProduto);
  $produto->upload($_POST['nomeProduto'], 
                  $_POST['descricaoProduto'], 
                  $_POST['codigoProduto'], 
                  $_POST['valorProduto'],  
                  $_FILES['imagemProduto'],
                  $_FILES['videoProduto']);
  
  // header("Location: class/Produto.php");
}

?>