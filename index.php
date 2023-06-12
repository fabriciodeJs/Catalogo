<?php
include_once('assets/php/class/DataBase.php');
$conn = new DataBase();
$pagina = 1;
// PEGA O NUMERO DA PAGINA VIA URL
if (isset($_GET['pagina'])) {
    $pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);
}

if (!$pagina) {
    $pagina = 1;
}

$limitePorPagina = 8;

$pagina_1 = ($pagina * $limitePorPagina) - $limitePorPagina;
// TOTAL DE PRODUTOS CADASTRADOS
$totalRegistro = $conn->query("SELECT COUNT(CODIGO) total FROM produto")->fetch(PDO::FETCH_BOTH)['total'];

$totalPaginas = ceil($totalRegistro / $limitePorPagina);


if (!empty($_GET['pesquisar'])) {
    $pesquisa = $_GET['pesquisar'];
    // PESQUISA NO BANCO DE DADOS
    $query = "SELECT DISTINCT produto.CODIGO, produto.NOME, produto.DESCRICAO, medias.src 
              FROM produto 
              JOIN medias ON produto.CODIGO = medias.produto_codigo 
              WHERE CODIGO LIKE '%$pesquisa%' OR NOME LIKE '%$pesquisa%' OR DESCRICAO LIKE '%$pesquisa%'
              GROUP BY produto.CODIGO";
} else {
    // LIMITA QUANTIDADE DE PRODUTOS POR PAGINA
    $query = "SELECT DISTINCT produto.CODIGO, produto.NOME, produto.DESCRICAO, medias.src 
              FROM produto 
              JOIN medias ON produto.CODIGO = medias.produto_codigo 
              GROUP BY produto.CODIGO 
              LIMIT $pagina_1, $limitePorPagina";
}

$consulta = $conn->query($query) or die($conn->$error);


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/logo-comemorativa-terwal.webp" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/index.css" media="all">
    <title>Catalogo</title>
</head>

<body>
    <header id="container-cabecalho">
        <div id="logo">
            <img src="assets/img/complementares/logo-comemorativa-terwal.webp" alt="Logo Terwal">
        </div>
        <div id="botao">
            <a href="login.php">Login</a>
        </div>
    </header>
    <main>
        <!-- <section id="container-pesquisar">
            <input type="search" name="pesquisar" id="pesquisar">
            <button onclick="pesquisarProduto()">pesquisar</button>
        </section> -->
        <section>
        <?php while ($dado = $consulta->fetch(PDO::FETCH_BOTH)) { ?>
                <div onclick="gerarPagina('<?php echo $dado['CODIGO'] ?>')" class="container-item">
                    <div class="card-item">
                        <div>
                            <img id="imagens" src="<?php echo $dado['src'] ?>" alt="<?php echo $dado['NOME'] ?>">
                        </div>
                        <h3><?php echo $dado['NOME'] ?></h3>
                        <p><?php echo $dado['DESCRICAO'] ?></p>
                    </div>
                </div>
            <?php } ?>
        </section>
        </section>
        <!--VERIFICA SE FOI FEITA A PESQUISA E RETIRA A PAGINACAO-->
        <?php if (empty($_GET['pesquisar'])) : ?>
            <div id="paginacao">
                <a href="?pagina=1">Primeira</a>

                <?php if ($pagina > 1) : ?>
                    <a href="?pagina=<?php echo $pagina - 1 ?>"><<</a>
                <?php endif; ?>

                <p><?php echo $pagina; ?></p>

                <?php if ($pagina < $totalPaginas) : ?>
                    <a href="?pagina=<?php echo $pagina + 1 ?>">>></a>
                <?php endif; ?>

                <a href="?pagina=<?php echo $totalPaginas ?>">Ultima</a>
            </div>
        <?php endif; ?>
    </main>
    <script src="https://kit.fontawesome.com/546ab0e97a.js" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>
