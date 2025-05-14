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
    <title>Perfil</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .custom-purple { color: #4d41d3 !important; }
    </style>
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container-fluid d-flex justify-content-center align-items-center flex-grow-1 p-0" style="min-height:calc(100vh - 90px);">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow-lg rounded-4 p-4 p-md-5 my-4 mx-auto">
                    <h1 class="custom-purple fs-3 fw-bold text-center mb-4">Atualizar Perfil</h1>
                    <form action="/perfil.php?action=atualizar" method="POST">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="nome" class="custom-purple fw-semibold mb-1">Nome:</label>
                                <input type="text" id="nome" name="nome" class="form-control form-control-sm rounded-3" value="<?= htmlspecialchars($usuario->getNome()) ?>" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="nomeUsuario" class="custom-purple fw-semibold mb-1">Nome de Usuário:</label>
                                <input type="text" id="nomeUsuario" name="nomeUsuario" class="form-control form-control-sm rounded-3" value="<?= htmlspecialchars($usuario->getNomeUsuario()) ?>" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="senha" class="custom-purple fw-semibold mb-1">Senha:</label>
                                <input type="password" id="senha" name="senha" class="form-control form-control-sm rounded-3" value="<?= htmlspecialchars($usuario->getSenha()) ?>" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="email" class="custom-purple fw-semibold mb-1">E-mail:</label>
                                <input type="email" id="email" name="email" class="form-control form-control-sm rounded-3" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="telefone" class="custom-purple fw-semibold mb-1">Telefone:</label>
                                <input type="text" id="telefone" name="telefone" class="form-control form-control-sm rounded-3" value="<?= htmlspecialchars($usuario->getTelefone()) ?>">
                            </div>
                            <?php if ($usuario->getPapel() === 'cliente'): ?>
                                <div class="col-12 col-md-6">
                                    <label for="cartaoCredito" class="custom-purple fw-semibold mb-1">Cartão de Crédito:</label>
                                    <input type="text" id="cartaoCredito" name="cartaoCredito" class="form-control form-control-sm rounded-3" value="<?= htmlspecialchars($perfilEspecifico->getCartaoCredito()) ?>">
                                </div>
                            <?php elseif ($usuario->getPapel() === 'fornecedor'): ?>
                                <div class="col-12 col-md-6">
                                    <label for="descricao" class="custom-purple fw-semibold mb-1">Descrição:</label>
                                    <input type="text" id="descricao" name="descricao" class="form-control form-control-sm rounded-3" value="<?= htmlspecialchars($perfilEspecifico->getDescricao()) ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-column flex-md-row gap-2 mt-5 justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold" style="max-width: 220px;">Atualizar Perfil</button>
                            <form action="/perfil.php?action=deletar" method="POST" class="w-100">
                                <button type="submit" class="btn btn-danger w-100 rounded-pill fw-semibold" style="max-width: 220px;">Deletar Conta</button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>