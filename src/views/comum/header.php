<?php
require_once __DIR__ . '/../../helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}
?>

<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem;">E-commerce</h1>
    </div>
    <div>
        <?php if (isset($_SESSION['user'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?></span>
            <?php if ($_SESSION['user']['papel'] === 'fornecedor'): ?>
                <a href="/estoque.php" style="margin-left: 10px;">Estoque</a>
            <?php endif; ?>
            <a href="/perfil.php" style="margin-left: 10px;">Profile</a>
            <a href="/logout.php" style="margin-left: 10px;">Logout</a>
        <?php else: ?>
            <a href="/login.php">Login</a>
        <?php endif; ?>
    </div>
</header>