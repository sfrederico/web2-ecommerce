<header class="navbar navbar-expand bg-white shadow-sm sticky-top">
    <div class="container-fluid flex-column flex-lg-row align-items-center gap-3 gap-lg-0">
        <a class="navbar-brand ms-lg-4 mb-2 mb-lg-0" href="/">
            <img src="/static/logo-horizontal.png" alt="Logo" style="height:64px;">
        </a>
        <nav class="w-100 d-flex justify-content-center justify-content-lg-end">
            <ul class="navbar-nav flex-column flex-lg-row gap-3 gap-lg-3 align-items-center mb-0">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn rounded-pill px-4 fw-semibold shadow-sm text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: #4d41d3; border: none;">
                            Welcome, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if ($_SESSION['user']['papel'] === 'fornecedor'): ?>
                                <li><a class="dropdown-item" href="/estoque.php">Estoque</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/perfil.php">Perfil</a></li>
                            <li><a class="dropdown-item" href="/logout.php">Sair</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn rounded-pill px-4 fw-semibold shadow-sm text-white me-4" href="/login.php" style="background: #4d41d3; border: none;">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>