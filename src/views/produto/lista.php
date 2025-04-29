<!-- filepath: /var/www/html/web2-ecommerce/src/views/produto/lista.php -->
<?php
require_once __DIR__ . '/../../../dao/ProdutoDao.php';
require_once __DIR__ . '/../../../model/Produto.php';
require_once __DIR__ . '/../../../helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit();
}

// Conexão com o banco de dados
$dbConnection = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');

// Instância do ProdutoDao
$produtoDao = new ProdutoDao($dbConnection);

// Obter todos os produtos
$produtos = $produtoDao->getTodosProdutos();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
</head>
<body>
    <h1>Lista de Produtos</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($produto->getId()); ?></td>
                        <td><?php echo htmlspecialchars($produto->getNome()); ?></td>
                        <td><?php echo htmlspecialchars($produto->getDescricao()); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nenhum produto cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>