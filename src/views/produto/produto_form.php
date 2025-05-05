<?php
require_once __DIR__ . '/../../helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Produto</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container my-5">
        <h1 class="text-center mb-4">Criar Produto</h1>
        <form action="/produto.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
            <input type="hidden" name="acao" value="criar">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do produto" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="4" placeholder="Digite a descrição do produto" required></textarea>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" class="form-control" min="0" required>
            </div>
            <div class="mb-3">
                <label for="preco" class="form-label">Preço</label>
                <input type="number" id="preco" name="preco" class="form-control" step="0.01" min="0" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="/estoque.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>