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
    <title>Profile</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/src/views/perfil/style.css"> <!-- Link para o CSS -->
</head>
<body class="profile-page">
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container">
        <h1 class="title">Update Profile</h1>
        <form action="/perfil.php?action=atualizar" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario->getNome()) ?>" required>
            </div>

            <div class="form-group">
                <label for="nomeUsuario">Nome de Usuário:</label>
                <input type="text" id="nomeUsuario" name="nomeUsuario" value="<?= htmlspecialchars($usuario->getNomeUsuario()) ?>" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" value="<?= htmlspecialchars($usuario->getSenha()) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario->getTelefone()) ?>">
            </div>

            <?php if ($usuario->getPapel() === 'cliente'): ?>
                <div class="form-group">
                    <label for="cartaoCredito">Cartão de Crédito:</label>
                    <input type="text" id="cartaoCredito" name="cartaoCredito" value="<?= htmlspecialchars($perfilEspecifico->getCartaoCredito()) ?>">
                </div>
            <?php elseif ($usuario->getPapel() === 'fornecedor'): ?>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($perfilEspecifico->getDescricao()) ?>">
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-success">Atualizar Perfil</button>
        </form>

        <div class="form-group">
            <form action="/perfil.php?action=deletar" method="POST">
                <button type="submit" class="btn btn-danger">Deletar Conta</button>
            </form>
        </div>
    </div>
</body>
</html>