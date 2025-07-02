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
                                <td><?= $item->getQuantidade() ?></td>
                                <td>R$ <?= number_format($item->getPrecoUnitario(), 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($item->getSubtotal(), 2, ',', '.') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger">Remover</button>
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
                <button class="btn btn-success">Finalizar pedido</button>
            </div>
        <?php endif; ?>
        
        <!-- Botão de teste para adicionar produto -->
        <div class="row justify-content-center mb-4 mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Teste - Adicionar Produto</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/carrinho.php">
                            <input type="hidden" name="produto_id" value="1">
                            <input type="hidden" name="quantidade" value="2">
                            <input type="hidden" name="preco_unitario" value="29.99">
                            <button type="submit" class="btn btn-primary">
                                Adicionar Produto ID 1 ao Carrinho (Teste)
                            </button>
                        </form>
                        <form method="POST" action="/carrinho.php">
                            <input type="hidden" name="produto_id" value="2">
                            <input type="hidden" name="quantidade" value="2">
                            <input type="hidden" name="preco_unitario" value="29.99">
                            <button type="submit" class="btn btn-primary mt-2">
                                Adicionar Produto ID 2 ao Carrinho (Teste)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>