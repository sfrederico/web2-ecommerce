<!-- filepath: /var/www/html/web2-ecommerce/src/views/produto/produto_form.php -->
<?php
require_once __DIR__ . '/../comum/header.php';
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
    <title>Cadastrar Novo Produto</title>
</head>
<body>
    <h1>Cadastrar Novo Produto</h1>
    <form action="/produto.php" method="POST">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>

        <button type="submit">Salvar Produto</button>
    </form>
</body>
</html>