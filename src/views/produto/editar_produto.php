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
    <title>Editar Produto</title>
</head>
<body>
    <h1>Editar Produto</h1>
    <form action="/produto.php?acao=atualizar&id=<?php echo $produto->getId(); ?>" method="POST">
        <input type="hidden" name="acao" value="atualizar">    
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto->getNome()); ?>" required>
        <br>
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($produto->getDescricao()); ?></textarea>
        <br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>