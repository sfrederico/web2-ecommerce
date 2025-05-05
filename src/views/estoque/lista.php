<?php
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
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/src/views/estoque/style.css">
</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container my-5">
        <h1 class="text-center mb-4">Estoque</h1>
        <div class="text-center mb-4">
            <a href="/produto.php" class="btn btn-primary">Criar Produto</a>
        </div>
        <form method="GET" action="/estoque.php" class="mb-4">
            <div class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por ID ou nome do produto" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                </div>
            </div>
        </form>
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
                                <p style="margin: 10px 0;">Quantidade em Estoque: <?php echo htmlspecialchars($produto->getEstoque()->getQuantidade() ?? 'N/A'); ?></p>
                                <p style="margin: 10px 0;">Preço: R$ <?php echo htmlspecialchars(number_format($produto->getEstoque()->getPreco() ?? 0, 2, ',', '.')); ?></p>
                                <p class="text-muted">ID: <?php echo htmlspecialchars($produto->getId()); ?></p>
                                <div class="d-flex justify-content-between">
                                    <a href="/produto.php?acao=editar&id=<?php echo $produto->getId(); ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <form action="/produto.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?php echo $produto->getId(); ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                    </form>
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