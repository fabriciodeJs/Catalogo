<?php
include('DataBase.php');

class Produto{

  function __construct(){
 
  }

  public function upload($nome, $codigo, $descricao, $valor, $imagens, $video){
    $db = new DataBase();
    $db->beginTransaction();
    // 1. Validação dos dados recebidos
    $this->validateData($nome, $descricao, $codigo, $valor, $imagens, $video);
    // 2. verificar se já existe o código no banco de dados
    $this->validateProduct($codigo);
    // 3. validar imagens
    $extensaoImg = $this->validateImagens($imagens);
    // 4. validar video
    $extensaoVideo = $this->validateVideo($video);
    // 5. gerar novos nomes para imagens e video
    $novosNomes = $this->newNameFolder($imagens);
    // 6. Criar pasta
    $pastaServ = $this->createFolder($codigo);
    // 7. Salvando o caminho das imagens servidor
    $caminhoImgServ = $this->filePathImgServ($pastaServ, $novosNomes, $extensaoImg);
    // 8. Salvando o caminho das imagens Index
    $caminhoImgIndex = $this->filePathImgIndex($codigo, $novosNomes, $extensaoImg);
    // 9. Salvando o caminho das video index e servidor
    $caminhoVideo = $this->filePathVideo($pastaServ, $codigo, $novosNomes, $extensaoVideo);
    // 8. Salvando no Servidor
    $salvo = $this->saveFile($caminhoImgServ, $caminhoVideo, $imagens, $video);
    if (!$salvo)
    die('Erro ao salvar arquivos no servidor!');
    //9. Salvando descrião do produto
    $this->setDescricao($nome, $descricao, $codigo, $valor);
    // 6. Salvando imagens e video do produto
    $this->setArquivos($caminhoImgIndex, $caminhoVideo, $codigo);


    //se der erro $conn->rollBack();

    
  }

  private function saveFile($caminhoImgServ, $caminhoVideo, $imagens, $video){
    foreach ($imagens['tmp_name'] as $key => $imagem) {
      if (!move_uploaded_file($imagem, $caminhoImgServ[$key]))
      throw new Exception("Error: Ao Salvar Arquivos no Servidor" . $imagem['name']);
    }

    if (!move_uploaded_file($video['tmp_name'], $caminhoVideo[0]))
    throw new Exception("Erro ao salvar arquivo de vídeo no servidor: " . $video['name']);

    return true;
  }

  private function filePathVideo($pastaServ, $codigo, $novosNomes, $extensaoVideo){
    $ultimoNome = end($novosNomes);
    $caminhoVideoServ = $pastaServ . $ultimoNome . '.' . $extensaoVideo;
    $caminhoVideoIndex = 'assets/img/teste/' . $codigo . '/' . $ultimoNome . '.' . $extensaoVideo;

    return [$caminhoVideoServ , $caminhoVideoIndex];
  }

  private function filePathImgServ($pastaServ, $novosNomes, $extensaoImg){
    for ($i=0; $i < count($novosNomes) - 1; $i++) { 
      $caminhoImgServ[$i] = $pastaServ . $novosNomes[$i] . "." . $extensaoImg[$i]; 
    }
 
    return $caminhoImgServ;
  }

  private function filePathImgIndex($codigo, $novosNomes, $extensaoImg){
    for ($i=0; $i < count($novosNomes) - 1; $i++) { 
      $caminhoImgIndex[$i] = 'assets/img/teste/' . $codigo . '/' . $novosNomes[$i] . '.' . $extensaoImg[$i];
    }
    return $caminhoImgIndex;
  }

  private function newNameFolder($imagens){
    $novoNomeImg = [];
    for ($i = 0; $i < count($imagens['name']) + 1; $i++) {
      $novoNomeImg[] = uniqid();
    }

    return $novoNomeImg;
  }

  private function createFolder($codigo) {
    $pastaServ = "../img/$codigo/";
    if (file_exists($pastaServ))
      throw new Exception("Error: Pasta de arquivos já existe no Servidor");
    if (!mkdir($pastaServ, 0755, true)) {
      throw new Exception("Ao criar a pasta para salvar a imagem na pasta $codigo no Servidor.");
    }

    return $pastaServ;
  }

  // private function deleteFolder($pastaServ) {
  //   if (file_exists($pastaServ)) {
  //     array_map('unlink', glob("$pastaServ/*.*"));
  //     rmdir($pastaServ);
  //   }
  //   return false;
  // }

  private function  validateImagens($imagens){
    $extensoesImg = [];
    foreach ($imagens['name'] as $key => $imagem) {
      $extensaoImg = strtolower(pathinfo($imagem, PATHINFO_EXTENSION));

      if ($extensaoImg != 'jpg' && $extensaoImg != 'png' && $extensaoImg != 'webp')
      throw new Exception("ADICIONE IMAGEM DO TIPO (PNG, JPG OU WEBP)" . $imagem);

      $extensoesImg[$key] = $extensaoImg;
    }

    return $extensoesImg;
  }

  private function  validateVideo($video){
    $nomeVideo = $video['name'];
    $extensaoVideo  = strtolower(pathinfo($nomeVideo, PATHINFO_EXTENSION));

    if ($extensaoVideo != 'mp4' && $extensaoVideo != 'mov' && $extensaoVideo != 'mkv')
      throw new Exception("ADICIONE VIDEO DO TIPO (MP4, MOV OU MKV)");

    return $extensaoVideo;
  }

  private function setDescricao($nome, $descricao, $codigo, $valor){
    $db = new DataBase();

    $query_produto = "INSERT INTO produto(CODIGO, NOME, DESCRICAO, VALOR) VALUES (?, ?, ?, ?)";
    $stmt_envioProduto = $db->prepare($query_produto);
    $stmt_envioProduto->bindValue(1, $codigo, PDO::PARAM_STR);
    $stmt_envioProduto->bindValue(2, $nome, PDO::PARAM_STR);
    $stmt_envioProduto->bindValue(3, $descricao, PDO::PARAM_STR);
    $stmt_envioProduto->bindValue(4, $valor, PDO::PARAM_STR);
    if (!$stmt_envioProduto->execute()) {
      throw new Exception("Ao fazer Upload dos dados!");
    }

    return true;
  }

  private function setArquivos($caminhoImgIndex, $caminhoVideo, $codigo){
    $db = new DataBase();
    $id_Produto = $db->lastInsertId();
    $query = "INSERT INTO imagens(ID, CODIGO_PRODUTO, video)
              VALUES (?,?,?)";
    $stmt_envioArquivo = $db->prepare($query);
    $stmt_envioArquivo->bindValue(1, $id_Produto, PDO::PARAM_STR);
    $stmt_envioArquivo->bindValue(2, $codigo, PDO::PARAM_STR);
    $stmt_envioArquivo->bindValue(3, $caminhoVideo[1], PDO::PARAM_STR);
    if (!$stmt_envioArquivo->execute())
    throw new Exception("Error: ao enviar Arquivos");
    
    $db->commit();
    // foreach ($caminhoImgIndex as $key => $imagens) {

    //   $stmt_envioArquivo->bindValue(3, $imagens, PDO::PARAM_STR);
    // }

    return true;
  }

  private function validateProduct($codigo){
    $db = new DataBase();
    $query_verificarCodigo = "SELECT COUNT(*) FROM produto WHERE CODIGO = ?";
    $stmt_verificarCodigo = $db->prepare($query_verificarCodigo);
    $stmt_verificarCodigo->bindValue(1, $codigo, PDO::PARAM_STR);
    $stmt_verificarCodigo->execute();
    if ($count = $stmt_verificarCodigo->fetchColumn() > 0) {
      throw new Exception("O código do produto já existe no banco de dados.");
    }

    return true;
  }

  private function validateData($nome, $descricao, $codigo, $valor, $imagens, $video){

    if (empty($nome)) {
      throw new Exception("O nome do produto Não foi fornecido.");
    }
    if (empty($descricao)) {
      throw new Exception("A descrição do produto é obrigatório.");
    }
    if (empty($codigo)) {
      throw new Exception("O Código do produto é obrigatório.");
    }
    if (empty($valor)) {
      throw new Exception("O Valor do produto é obrigatório.");
    }
    if (empty($imagens)) {
      throw new Exception("A Imagem do produto é obrigatório.");
    }
    if (empty($video)) {
      throw new Exception("O Video do produto é obrigatório.");
    }

    return true;
  }

 

}
