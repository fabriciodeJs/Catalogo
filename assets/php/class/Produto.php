<?php
include_once('../bd/DataBase.php.php');

class Produto {
  private $nomeProduto;
  private $descricaoProduto;
  private $codigoProduto; 
  private $valorProduto;
  private $imagemProduto = [];
  private $videoProduto;

  public function Produto() {

  }

  function __construct($nome, $descricao, $codigo, $valor, $imagens, $video)  {
    $this->nomeProduto = $nome;
    $this->descricaoProduto = $descricao;
    $this->codigoProduto = $codigo;
    $this->valorProduto = $valor;
    $this->imagemProduto = $imagens;
    $this->videoProduto = $video;

  }

  public function upload($nome, $descricao, $codigo, $valor, $imagens, $video) {
    // 1. Validação dos dados recebidos
    $this->validateData($nome, $descricao, $codigo, $valor, $imagens, $video);
    // 2. verificar se já existe o código no banco de dados
    $this->validateProduct($codigo);
    // 3. validar imagens
    $extensaoImg = $this->validateImagens($imagens, $codigo);
    // 3. validar video
    $extensaoVideo = $this->validateVideo($video, $codigo);
    // gerar novos nomes para imagens e video
    $novosNomes = $this->newNameFolder($imagens);
    // 4. Criar pasta
    $pastaServ = $this->createFolder($codigo);
    // 5. Salvando o caminho das imagens
    $caminho = $this->filePath($pastaServ, $codigo, $novosNomes, $imagens, $extensaoImg, $extensaoVideo);
    
   
    // // 5. Salvando descrião do produto
    // $this->uploadDescricao($nome, $descricao, $codigo, $valor);
    // // 6. Salvando imagens e video do produto
    // $this->uploadArquivos($imagens, $video);
    

  }

  private function filePath($pastaServ, $codigo, $novosNomes, $imagens, $extensaoImg, $extensaoVideo) {
    $caminhos = [];
    
    foreach ($novosNomes as $nome) {
      $caminhoImg = $pastaServ . $nome . '.' . $extensaoImg;
      $caminhoIndex = 'assets/img/teste/' . $codigo . '/' . $nome . '.' . $extensaoImg;
      $caminhos['imagens'][] = [
        'caminhoServidor' => $caminhoImg,
        'caminhoIndex' => $caminhoIndex
      ];
    }
    
    $ultimoNome = end($novosNomes);
    $caminhoVideoServidor = $pastaServ . $ultimoNome . '.' . $extensaoVideo;
    $caminhoVideoIndex = 'assets/img/teste/' . $codigo . '/' . $ultimoNome . '.' . $extensaoVideo;
    $caminhos['video'] = [
      'caminhoServidor' => $caminhoVideoServidor,
      'caminhoIndex' => $caminhoVideoIndex
    ];
  
    return $caminhos;
  }

  private function newNameFolder($imagens){
    $novoNomeImg = null;
    for ($i = 0; $i < count($imagens['name'] + 1); $i++) {
      $novoNomeImg += uniqid();
    }
   
    return $novoNomeImg;
  }

  private function createFolder($codigo) {
    $pastaServ = "../img/teste/$codigo/";

    if (!mkdir($pastaServ, 0755, true)) {
      throw new Exception("Ao criar a pasta para salvar a imagem,
                          verifique se já não existe uma pasta $codigo no Servidor.");
    }

    return $pastaServ;
  }

  private function deleteFolder($pastaServ) {
    if (is_dir($pastaServ)) {
      array_map('unlink', glob("$pastaServ/*.*"));
      rmdir($pastaServ);
    }
  }

  private function  validateImagens($imagens){
    foreach ($imagens as $key => $value) {
     $nomeImg = $imagens['name'][$key];
     $extensaoImg = strtolower(pathinfo($nomeImg, PATHINFO_EXTENSION));

     if ($extensaoImg  != 'jpg' && $extensaoImg != 'png' && $extensaoImg != 'webp')
      throw new Exception("ADICIONE IMAGEM DO TIPO (PNG, JPG OU WEBP)");
      
    }

    return $extensaoImg;
  }

  private function  validateVideo($video){
    $nomeVideo = $video['name'];
    $extensaoVideo  = strtolower(pathinfo($nomeVideo, PATHINFO_EXTENSION));

     if ($extensaoVideo != 'mp4' && $extensaoVideo != 'mov' && $extensaoVideo != 'mkv')
      throw new Exception("ADICIONE VIDEO DO TIPO (MP4, MOV OU MKV)");

    return true;
  }

  private function uploadDescricao($nome, $descricao, $codigo, $valor) {
    $db = new DataBase();
    if ($db->uploadDescricao($nome, $descricao, $codigo, $valor)) {
      return true;
    }

    return false;
  }

  private function uploadArquivos($imagens, $video) {
    $db = new DataBase();
    if ($db->uploadArquivos($imagens, $video)) {
      return true;
    }

    return false;
  }

  private function validateProduct($codigo) {
    $db = new DataBase();
    if ($db->validateProduct($codigo)) {
      return true;
    }  

    return false;
  }

  private function validateData($nome, $descricao, $codigo, $valor, $imagens, $video) {
    
    if (empty($nomeProduto)) {
      throw new Exception("O nome do produto é obrigatório.");
    }
    if (empty($descricaoProduto)) {
      throw new Exception("A descrição do produto é obrigatório.");
    }
    if (empty($codigoProduto)) {
      throw new Exception("O Código do produto é obrigatório.");
    }
    if (empty($valorProduto)) {
      throw new Exception("O Valor do produto é obrigatório.");
    }
    if (empty($imagemProduto)) {
      throw new Exception("A Imagem do produto é obrigatório.");
    }
    if (empty($videoProduto)) {
      throw new Exception("O Video do produto é obrigatório.");
    }

    return true;
  } 

  // function getNomeProduto() {
  //   return $this->nomeProduto;
  // }

  // function getDescricaoProduto() {
  //   return $this->descricaoProduto;
  // }

  // function getCodigoProduto() {
  //   return $this->codigoProduto;
  // }

  // function getValorProduto() {
  //   return $this->valorProduto;
  // }

  // function getImagemProduto() {
  //   return $this->imagemProduto;
  // }

  // function getVideoProduto() {
  //   return $this->videoProduto;
  // }

  // function setNomeProduto ($nome) {
  //     $this->nomeProduto = $nome;
  // }
  // function setDescricaoProduto ($descricao) {
  //     $this->descricaoProduto = $descricao;
  // }
  // function setCodigoProduto ($codigo) {
  //     $this->codigoProduto = $codigo;
  // }
  // function setValorProduto ($valor) {
  //     $this->valorProduto = $valor;
  // }
  // function setImagemProduto ($imagens) {
  //     $this->imagemProduto = $imagens;
  // }
  // function setVideoProduto ($video) {
  //     $this->videoProduto = $video;
  // }


}
