<!-- filepath: /var/www/html/web2-ecommerce/src/views/produto/salvar_produto.php -->
<?php
require_once __DIR__ . '/../../helpers/SessionHelper.php';
require_once __DIR__ . '/../../dao/ProdutoDao.php';
require_once __DIR__ . '/../../model/Produto.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['papel'] !== 'fornecedor') {
    header("Location: /login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    // Conexão com o banco de dados
    $dbConnection = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');

    $produtoDao = new ProdutoDao($dbConnection);
    $produto = new Produto($nome, $descricao);

    if ($produtoDao->salvarProduto($produto)) {
        // Redirecionar para a lista de produtos após o sucesso
        header("Location: /src/views/produto/lista.php?success=1");
        exit();
    } else {
        // Redirecionar de volta ao formulário com uma mensagem de erro
        header("Location: /src/views/produto/produto_form.php?error=1");
        exit();
    }
}
?>