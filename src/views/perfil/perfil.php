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
    <title>Profile</title>
</head>
<body>
    <h1>Update Profile</h1>
    <form action="../perfil.php" method="POST">
        <label for="nome">Name:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario->getNome()); ?>" required>

        <label for="nomeUsuario">Username:</label>
        <input type="text" id="nomeUsuario" name="nomeUsuario" value="<?php echo htmlspecialchars($usuario->getNomeUsuario()); ?>" required>

        <label for="senha">Password:</label>
        <input type="password" id="senha" name="senha" value="<?php echo htmlspecialchars($usuario->getSenha()); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario->getEmail()); ?>" required>
    
        <?php if ($usuario->getPapel() === 'cliente'): ?>
            <label for="endereco">Credit card:</label>
            <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($cliente->getCartaoCredito()); ?>" required>
        <?php elseif ($usuario->getPapel() === 'fornecedor'): ?>
            <label for="empresa">Descricao:</label>
            <input type="text" id="empresa" name="empresa" value="<?php echo htmlspecialchars($fornecedor->getDescricao()); ?>" required>
        <?php endif; ?>

        <button type="submit">Update</button>
    </form>
</body>
</html>