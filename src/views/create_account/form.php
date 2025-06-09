<?php
if (!isset($error)) {
    $error = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        @media (max-width: 991.98px) {
            .order-mobile-1 { order: 1 !important; }
            .order-mobile-2 { order: 2 !important; }
            .main-card-mobile { border-radius: 0 !important; }
            .form-mobile-bg { padding: 1.5rem 1rem !important; }
        }
        @media (min-width: 992px) {
            .order-lg-1 { order: 1 !important; }
            .order-lg-2 { order: 2 !important; }
            .main-card-mobile { border-radius: 2rem !important; }
        }
        @media (max-width: 575.98px) {
            .form-row-mobile {
                flex-direction: column !important;
            }
            .form-row-mobile > .col-6 {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body class="bg-light d-flex flex-column">
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container-fluid d-flex justify-content-center align-items-center p-0 flex-grow-1" style="min-height:calc(100vh - 90px);">
        <div class="d-flex flex-column flex-lg-row w-100 justify-content-center align-items-stretch main-card-mobile" style="max-width:900px; overflow:hidden; box-shadow:0 4px 32px rgba(0,123,255,0.10);">
            <div class="bg-white d-flex flex-column justify-content-center align-items-center w-100 w-lg-50 p-4 p-lg-5 order-mobile-2 order-lg-1">
                <img src="/static/logo-completa.png" alt="Logo" class="img-fluid mb-3" style="max-width:80%; filter:drop-shadow(0 4px 24px rgba(0,123,255,0.10));">
            </div>
            <div class="d-flex flex-fill w-100 w-lg-50 justify-content-center align-items-center order-mobile-1 order-lg-2 form-mobile-bg" style="background:#4d41d3;">
                <div class="w-100" style="max-width:340px;">
                    <h1 class="fw-bold mb-1 text-center" style="color:#fff; font-size:1.5rem;">Criar conta</h1>
                    <?php if ($error): ?>
                        <p class="error-message text-center" style="color:#ffb3b3;">Erro: <?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                    <form action="create_account.php" method="POST" class="w-100">
                        <div class="mb-1">
                            <label for="nomeUsuario" class="form-label text-white mb-1" style="font-size:0.85rem;">Usuário:</label>
                            <input type="text" id="nomeUsuario" name="nomeUsuario" class="form-control rounded-pill py-0 px-2 fs-7" style="font-size:0.85rem; height: 26px;" required>
                        </div>
                        <div class="mb-1">
                            <label for="senha" class="form-label text-white mb-1" style="font-size:0.85rem;">Senha:</label>
                            <input type="password" id="senha" name="senha" class="form-control rounded-pill py-0 px-2 fs-7" style="font-size:0.85rem; height: 26px;" required>
                        </div>
                        <div class="mb-1">
                            <label for="nome" class="form-label text-white mb-1" style="font-size:0.85rem;">Nome:</label>
                            <input type="text" id="nome" name="nome" class="form-control rounded-pill py-0 px-2 fs-7" style="font-size:0.85rem; height: 26px;" required>
                        </div>
                        <div class="row mb-1 g-1 form-row-mobile">
                            <div class="col-6">
                                <label for="telefone" class="form-label text-white mb-1" style="font-size:0.85rem;">Telefone:</label>
                                <input type="text" id="telefone" name="telefone" class="form-control rounded-pill py-0 px-2 fs-7" style="font-size:0.85rem; height: 26px;" required>
                            </div>
                            <div class="col-6">
                                <label for="email" class="form-label text-white mb-1" style="font-size:0.85rem;">E-mail:</label>
                                <input type="email" id="email" name="email" class="form-control rounded-pill py-0 px-2 fs-7" style="font-size:0.85rem; height: 26px;" required>
                            </div>
                        </div>
                        <div class="row mb-1 g-1 align-items-end form-row-mobile">
                            <div class="col-6">
                                <label for="papel" class="form-label text-white mb-1" style="font-size:0.85rem;">Perfil:</label>
                                <select id="papel" name="papel" class="form-select rounded-pill py-0 px-2 fs-7" style="font-size:0.85rem; height: 26px;" required>
                                    <option value="cliente">Cliente</option>
                                    <option value="fornecedor">Fornecedor</option>
                                </select>
                            </div>
                            <div class="col-6" id="dynamic-field-wrapper"></div>
                        </div>
                        <button id="create-account-submit-btn" type="submit" class="btn w-100 rounded-pill fw-semibold fs-7 py-1 mt-4" style="background:#fff; color:#007bff; font-size:0.90rem; height: 28px;">Criar conta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleField = document.getElementById('papel');
            const dynamicFieldWrapper = document.getElementById('dynamic-field-wrapper');

            function addDynamicField(role) {
                dynamicFieldWrapper.innerHTML = '';
                if (role === 'fornecedor') {
                    dynamicFieldWrapper.innerHTML = `
                        <label for="descricao" class="form-label text-white mb-1" style="font-size:0.85rem;">Descrição:</label>
                        <input type="text" id="descricao" name="descricao" class="form-control rounded-pill py-0 px-2 fs-7" style="font-size:0.85rem; height: 26px;" required>
                    `;
                } else if (role === 'cliente') {
                    dynamicFieldWrapper.innerHTML = `
                        <label for="cartaoCredito" class="form-label text-white mb-1" style="font-size:0.85rem;">Cartão de crédito:</label>
                        <input type="text" id="cartaoCredito" name="cartaoCredito" class="form-control rounded-pill py-0 px-2 fs-7" style="font-size:0.85rem; height: 26px;" required>
                    `;
                }
            }
            addDynamicField(roleField.value);
            roleField.addEventListener('change', function () {
                addDynamicField(roleField.value);
            });
        });
    </script>
</body>
</html>