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
    <title>Criar Produto</title>
</head>
<body>
    <h1>Criar Produto</h1>
    <form action="/produto.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br>
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>
        <br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>