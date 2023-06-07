<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
</head>

<body>
  <form enctype="multipart/form-data" action="assets/php/valida.php" method="post">
    <div>
      <label class="labels" for="codigoProduto">Código Do Produto: </label>
      <input class="inputs" type="text" name="codigoProduto" id="codigoProduto" required>
    </div>
    <div>
      <label class="labels" for="nomeProduto">Nome Do Produto: </label>
      <input class="inputs" type="text" name="nomeProduto" id="nomeProduto" required>
    </div>
    <div>
      <label class="labels" for="descricaoProduto">Descrição Do Produto: </label>
      <input class="inputs" type="text" name="descricaoProduto" id="descricaoProduto" required>
    </div>

    <div>
      <label class="labels" for="valorProduto">Valor Do Produto: </label>
      <input class="inputs" type="text" name="valorProduto" id="valorProduto" required>
    </div>

    <div>
      <label class="labels" for="imagemProduto">Imagem Do Produto: </label>
      <input class="inputs" type="file" multiple="multiple" name="imagemProduto[]" id="imagemProduto">
    </div>
    <div>
      <label class="labels" for="videoProduto">Video Do Produto: </label>
      <input class="inputs" type="file" name="videoProduto" accept="video/mp4, video/mov, video/mkv" id="videoProduto">
    </div>
    <input id="botaoSubmit" type="submit" value="Cadastrar">
  </form>
</body>

</html>