<?php
include('DataBase.php');

class Produto{

  function __construct(){
 
  }

  public function upload($nome, $descricao, $codigo, $valor, $imagens, $video){
    // 1. Validação dos dados recebidos
    $this->validateData($nome, $descricao, $codigo, $valor, $imagens, $video);
    // 2. verificar se já existe o código no banco de dados
    $this->validateProduct($codigo);
    // 3. validar imagens
    $extensaoImg[] = $this->validateImagens($imagens);
    // 4. validar video
    $extensaoVideo = $this->validateVideo($video);
    // 5. gerar novos nomes para imagens e video
    $novosNomes = $this->newNameFolder($imagens);
    // 6. Criar pasta
    $pastaServ = $this->createFolder($codigo);
    // 7. Salvando o caminho das imagens
    $caminho = $this->filePath($pastaServ, $codigo, $novosNomes, $extensaoImg, $extensaoVideo);
    // 8. Salvando no Servidor
    $salvo = $this->saveFile($caminho, $imagens);
    //9. Salvando descrião do produto
    $this->setDescricao($nome, $descricao, $codigo, $valor);
    // 6. Salvando imagens e video do produto
    $this->uploadArquivos($imagens, $video);
  }

  private function saveFile($caminho, $imagens){
    foreach ($imagens["tmp_name"] as $key => $imagem) {
      $caminhoServ = $caminho['imagens'][$key]['caminhoServidor'];
      if (!move_uploaded_file($imagem, $caminhoServ))
        throw new Exception("Error: Ao Salvar Arquivos no Servidor" . $imagem['name'][$key]);
    }

    $caminhoVideoServ = $caminho['video']['caminhoServidor'];
    if (!move_uploaded_file($imagens['tmp_name']['video'], $caminhoVideoServ))
      throw new Exception("Erro ao salvar arquivo de vídeo no servidor: " . $imagens['name']['video']);

    return true;
  }

  private function filePath($pastaServ, $codigo, $novosNomes, $extensaoImg, $extensaoVideo){
    $caminhos = [];
    $extensaoImagem = $extensaoImg;
    var_dump($extensaoImagem);
    foreach ($novosNomes as $key => $nome) {
      $caminhoImgServ = $pastaServ . $nome . '.' . $extensaoImagem[$key];
      $caminhoImgIndex = 'assets/img/teste/' . $codigo . '/' . $nome . '.' . $extensaoImagem[$key];
      $caminhos['imagens'][$key] = [
        'caminhoServidor' => $caminhoImgServ,
        'caminhoIndex' => $caminhoImgIndex
      ];
    }

    $ultimoNome = end($novosNomes);
    $caminhoVideoServ = $pastaServ . $ultimoNome . '.' . $extensaoVideo;
    $caminhoVideoIndex = 'assets/img/teste/' . $codigo . '/' . $ultimoNome . '.' . $extensaoVideo;
    $caminhos['video'] = [
      'caminhoServidor' => $caminhoVideoServ,
      'caminhoIndex' => $caminhoVideoIndex
    ];

    return $caminhos;
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
      $nomeImg = $imagem;
      $extensaoImg = strtolower(pathinfo($nomeImg, PATHINFO_EXTENSION));
      if ($extensaoImg != 'jpg' && $extensaoImg != 'png' && $extensaoImg != 'webp')
        throw new Exception("ADICIONE IMAGEM DO TIPO (PNG, JPG OU WEBP)" . var_dump($imagem[$key]));

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

  private function uploadArquivos($imagens, $video){
    $db = new DataBase();
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
