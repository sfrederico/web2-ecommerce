<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container my-5">
        <h1 class="text-center mb-4">Meu Carrinho</h1>
        
        <!-- Mensagens de feedback -->
        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?= $_SESSION['sucesso'] ?></div>
            <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['erro'] ?>
                <?php if (isset($_SESSION['itens_sem_estoque'])): ?>
                    <?php foreach ($_SESSION['itens_sem_estoque'] as $item): ?>
                        <div>
                            Item: <?= htmlspecialchars($item->getProduto()->getNome()) ?> - Quantidade disponível: <?= htmlspecialchars($item->getProduto()->getEstoque()->getQuantidade()) ?>
                        </div>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['itens_sem_estoque']); ?>
                <?php endif; ?>
            </div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>
        
        <?php if (empty($itens)): ?>
            <div class="alert alert-info">
                <h4>Carrinho vazio</h4>
                <p>Adicione produtos ao seu carrinho para continuar.</p>
                <a href="/" class="btn btn-primary">Continuar comprando</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Subtotal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($item->getProduto()->getFoto()): ?>
                                            <img src="<?= htmlspecialchars($item->getProduto()->getFoto()) ?>" 
                                                 alt="<?= htmlspecialchars($item->getProduto()->getNome()) ?>" 
                                                 class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?= htmlspecialchars($item->getProduto()->getNome()) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($item->getProduto()->getDescricao()) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <form method="POST" action="/carrinho.php" class="d-flex align-items-center" style="max-width: 120px;">
                                        <input type="hidden" name="action" value="alterar_quantidade">
                                        <input type="hidden" name="item_id" value="<?= $item->getId() ?>">
                                        <input type="number" name="quantidade" value="<?= $item->getQuantidade() ?>" 
                                               min="1" class="form-control form-control-sm me-1" style="width: 60px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">OK</button>
                                    </form>
                                </td>
                                <td>R$ <?= number_format($item->getPrecoUnitario(), 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($item->getSubtotal(), 2, ',', '.') ?></td>
                                <td>
                                    <form method="POST" action="/carrinho.php" style="display: inline;">
                                        <input type="hidden" name="action" value="remover">
                                        <input type="hidden" name="item_id" value="<?= $item->getId() ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Tem certeza que deseja remover este item?')">
                                            Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <a href="/" class="btn btn-secondary">Continuar comprando</a>
                <form method="POST" action="/meus-pedidos.php" style="display: inline;">
                    <input type="hidden" name="action" value="confirmar_pedido">
                    <button type="submit" class="btn btn-success">Finalizar pedido</button>
                </form>
            </div>
        <?php endif; ?>
</body>
</html>