<?php
require_once __DIR__ . '/../../helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit();
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
    <title>Estoque</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/src/views/estoque/style.css">
    <style>
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
        <h1 class="text-center mb-4">Estoque</h1>
        <div class="text-center mb-4">
            <a href="/produto.php" class="btn text-white rounded-pill" style="background: #4d41d3" >Criar Produto</a>
        </div>
        <form method="GET" action="/estoque.php" class="mb-4">
            <div class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control rounded-pill" placeholder="Buscar por ID ou nome do produto" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn text-white rounded-pill w-100" style="background: #4d41d3">Buscar</button>
                </div>
            </div>
        </form>
        <?php if (empty($produtos)): ?>
            <div class="alert alert-warning text-center" role="alert">
                O estoque está vazio. Nenhum produto encontrado.
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
                            <div class="d-flex justify-content-between mt-2">
                                <a href="/produto.php?acao=editar&id=<?php echo $produto->getId(); ?>" class="btn btn-sm btn-outline-primary rounded-pill">Editar</a>
                                <form action="/produto.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?php echo $produto->getId(); ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Excluir</button>
                                </form>
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