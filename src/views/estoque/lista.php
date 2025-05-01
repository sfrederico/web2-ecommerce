<?php
require_once __DIR__ . '/../comum/header.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <link rel="stylesheet" href="/src/views/estoque/style.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Estoque</h1>
        <div class="text-center mb-4">
            <a href="/produto.php" class="btn btn-primary">Criar Produto</a>
        </div>
        <?php if (empty($produtos)): ?>
            <div class="alert alert-warning text-center" role="alert">
                O estoque está vazio. Nenhum produto encontrado.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Produto: <?php echo htmlspecialchars($produto->getNome()); ?></h5>
                                <p class="card-text">Descrição: <?php echo htmlspecialchars($produto->getDescricao()); ?></p>
                                <p class="text-muted">ID: <?php echo htmlspecialchars($produto->getId()); ?></p>
                                <div class="d-flex justify-content-between">
                                    <a href="/produto.php?acao=editar&id=<?php echo $produto->getId(); ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <a href="/produto.php?acao=excluir&id=<?php echo $produto->getId(); ?>" class="btn btn-sm btn-outline-danger">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>