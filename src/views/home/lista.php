<?php
require_once __DIR__ . '/../../helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit();
}

// Recupera o usuário logado e verifica se é fornecedor
$usuarioLogado = $_SESSION['user'] ?? null;
$isFornecedor = false;
if ($usuarioLogado && isset($usuarioLogado['papel']) && strtolower($usuarioLogado['papel']) === 'fornecedor') {
    $isFornecedor = true;
}

// Paginação
$produtosPorPagina = 9;
$totalProdutos = count($produtos);
$totalPaginas = max(1, ceil($totalProdutos / $produtosPorPagina));
$paginaAtual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$paginaAtual = max(1, min($paginaAtual, $totalPaginas));
$inicio = ($paginaAtual - 1) * $produtosPorPagina;
$produtosPagina = array_slice($produtos, $inicio, $produtosPorPagina);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/src/views/estoque/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background:rgb(255, 255, 255) !important;
        }
        .titulo-produto {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .descricao-produto {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container my-5">
        <h1 class="text-center mb-4" style="
            font-family: 'Orbitron', 'Segoe UI', Arial, sans-serif;
            font-size: 5;
            letter-spacing: 2px;
            color: #4d41d3;
            background: linear-gradient(90deg, #4d41d3 0%, #4d41d3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;">Bem-vindo ao SHEEP COMMERCE</h1>
        <form method="GET" action="/home.php" class="mb-4">
            <div class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control rounded-pill" placeholder="Buscar por ID ou nome do produto" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn text-white rounded-pill w-100" style="background: #4d41d3">Buscar</button>
                </div>
            </div>
        </form>
        <?php if ($isFornecedor): ?>
                    <div class="fw-medium alert text-center my-5" style="background:rgb(225, 225, 248);" role="alert">
                        Como fornecedor, os produtos não podem ser adicionados ao carrinho.
                    </div>
                <?php endif; ?>
        <?php if (empty($produtos)): ?>
            <div class="alert alert-warning text-center" role="alert">
                A lista de produtos está vazia, aguarde novidades!
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($produtosPagina as $produto): ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm p-4" style="height: 300px;">
                            <div class="row g-0 align-items-center mb-2">
                                <div class="col-4">
                                    <?php if ($produto->getFoto()): ?>
                                        <img src="<?php echo htmlspecialchars($produto->getFoto()); ?>" alt="Foto do produto" class="img-thumbnail" style="width: 90px; height: 90px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary d-flex justify-content-center align-items-center" style="width: 90px; height: 90px; border-radius: 0.375rem; color: #fff;">Sem foto</div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-8">
                                    <h5 class="titulo-produto"><?php echo htmlspecialchars($produto->getNome()); ?></h5>
                                    <span class="fw-bold" style="color: #4d41d3">R$ <?php echo htmlspecialchars(number_format($produto->getEstoque()->getPreco() ?? 0, 2, ',', '.')); ?></span><br>
                                    <span class="text-muted small">Qtd. em estoque: <?php echo htmlspecialchars($produto->getEstoque()->getQuantidade() ?? 'N/A'); ?></span>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <span class="text-muted small">Descrição:</span><br>
                                <span class="descricao-produto"><?php echo htmlspecialchars($produto->getDescricao()); ?></span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small">Fornecedor:</span><br>
                                <span class="descricao-produto"><?php echo htmlspecialchars($produto->getFornecedor()->getUsuario()->getNome() ?? 'N/A'); ?></span>
                            </div>
                            <div class="d-flex justify-content-center mt-2">
                                <?php if ($isFornecedor): ?>
                                    <button class="btn btn-secondary btn-sm rounded-pill" disabled>Adicionar ao carrinho</button>
                                <?php elseif (($produto->getEstoque()->getQuantidade() ?? 0) > 0): ?>
                                    <form method="POST" action="/carrinho.php" class="d-inline">
                                        <input type="hidden" name="acao" value="adicionar">
                                        <input type="hidden" name="produto_id" value="<?php echo $produto->getId(); ?>">
                                        <button type="submit" class="btn text-white btn-sm rounded-pill" style="background: #4d41d3;">Adicionar ao carrinho</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm rounded-pill" disabled>Indisponível</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <nav aria-label="Navegação de página" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item<?php if ($paginaAtual <= 1) echo ' disabled'; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $paginaAtual - 1])); ?>" tabindex="-1">Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item<?php if ($i == $paginaAtual) echo ' active'; ?>">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item<?php if ($paginaAtual >= $totalPaginas) echo ' disabled'; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $paginaAtual + 1])); ?>">Próxima</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</body>

</html>