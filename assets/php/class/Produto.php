<?php
include('DataBase.php');

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

  public function upload($nome, $descricao, $codigo, $valor, $imagens, $video) {
    // 1. Validação dos dados recebidos
    $this->validateData($nome, $descricao, $codigo, $valor, $imagens, $video);
    // 2. verificar se já existe o código no banco de dados
    $this->validateProduct($codigo);
    // 3. validar imagens
    $extensaoImg[] = $this->validateImagens($imagens);
    // 3. validar video
    $extensaoVideo = $this->validateVideo($video);
    // gerar novos nomes para imagens e video
    $novosNomes = $this->newNameFolder($imagens);
    // 4. Criar pasta
    $pastaServ = $this->createFolder($codigo);
    // 5. Salvando o caminho das imagens
    $caminho = $this->filePath($pastaServ, $codigo, $novosNomes, $extensaoImg, $extensaoVideo);
    // 6. Salvando no Servidor
    $salvo = $this->saveFile($caminho, $imagens);
    // 5. Salvando descrião do produto

    echo '<h1>Sucesso</h1>';
    // $this->uploadDescricao($nome, $descricao, $codigo, $valor);
    // // 6. Salvando imagens e video do produto
    // $this->uploadArquivos($imagens, $video);
    

  }

  private function saveFile($caminho, $imagens){
    foreach ($imagens["tmp_name"] as $key => $imagem) {
      $caminhoServ = $caminho['imagens'][$key]['caminhoServidor'];
     if(!move_uploaded_file($imagem, $caminhoServ))
      throw new Exception("Error: Ao Salvar Arquivos no Servidor" . $imagem['name'][$key]);
    }

    $caminhoVideoServ = $caminho['video']['caminhoServidor'];
    if (!move_uploaded_file($imagens['tmp_name']['video'], $caminhoVideoServ)) 
    throw new Exception("Erro ao salvar arquivo de vídeo no servidor: " . $imagens['name']['video']);

    return true;
  }

  private function filePath($pastaServ, $codigo, $novosNomes, $extensaoImg, $extensaoVideo) {
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
    $pastaServ = "../img/teste/$codigo/";
    if(file_exists($pastaServ))
    throw new Exception("Error: Pasta de arquivos já existe no Servidor");

    if (!mkdir($pastaServ, 0755, true)) {
      throw new Exception("Ao criar a pasta para salvar a imagem,
                          verifique se já não existe uma pasta $codigo no Servidor.");
    }

    return $pastaServ;
  }

  private function deleteFolder($pastaServ) {
    if (file_exists($pastaServ)) {
      array_map('unlink', glob("$pastaServ/*.*"));
      rmdir($pastaServ);
    }
    return false;
  }

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
