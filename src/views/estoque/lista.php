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
</head>
<body>
    <h1>Estoque</h1>
    <button>
        <a href="/produto.php" style="margin: 20px 0; padding: 10px 20px; text-decoration: none;">Criar Produto</a>
    </button>
    <?php if (empty($produtos)): ?>
        <p>O estoque está vazio. Nenhum produto encontrado.</p>
    <?php else: ?>
        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
            <?php foreach ($produtos as $produto): ?>
                <div style="border: 1px solid #ddd; padding: 10px; width: 200px; border-radius: 5px;">
                    <h2 style="font-size: 1.2rem; margin: 0;">Produto: <?php echo htmlspecialchars($produto->getNome()); ?></h2>
                    <p style="margin: 10px 0;">Descrição: <?php echo htmlspecialchars($produto->getDescricao()); ?></p>
                    <p style="font-size: 0.9rem; color: #555;">ID: <?php echo htmlspecialchars($produto->getId()); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>