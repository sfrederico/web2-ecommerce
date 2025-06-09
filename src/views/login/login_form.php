<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light d-flex flex-column">
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container-fluid d-flex justify-content-center align-items-center p-0" style="min-height:calc(100vh - 90px);">
        <div class="d-flex flex-column flex-lg-row w-100 justify-content-center align-items-stretch" style="max-width:900px; border-radius:2rem; overflow:hidden; box-shadow:0 4px 32px rgba(77,65,211,0.10);">
            <div class="bg-white d-flex flex-column justify-content-center align-items-center w-100 w-lg-50 p-4 p-lg-5">
                <h2 class="fw-bold mb-4" style="color:#4d41d3;">Bem-vindo de volta!</h2>
                <form method="POST" action="../login.php" autocomplete="on" class="w-100" style="max-width:340px;">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuário</label>
                        <input type="text" class="form-control rounded-pill py-2 px-3 fs-5" id="username" name="username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control rounded-pill py-2 px-3 fs-5" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn w-100 rounded-pill fw-semibold fs-5 py-2 mt-2" style="background:#4d41d3; color:#fff;">Entrar</button>
                </form>
                <p class="mt-3 text-center">Não tem uma conta? <a href="../create_account.php" class="fw-semibold" style="color:#4d41d3;">Crie uma aqui</a>.</p>
            </div>
            <div class="d-flex flex-fill w-100 w-lg-50 justify-content-center align-items-center" style="background:#4d41d3;">
                <img src="/static/logo-completa-branco.png" alt="Logo do sistema" class="img-fluid" style="max-width:80%; filter:drop-shadow(0 4px 24px rgba(77,65,211,0.15));">
            </div>
        </div>
    </div>
</body>
</html>
