<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2>Meus Pedidos</h2>
                
                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['erro'] ?></div>
                    <?php unset($_SESSION['erro']); ?>
                <?php endif; ?>
                
                <?php if (empty($pedidos)): ?>
                    <div class="alert alert-warning mt-4">
                        <h4>Nenhum pedido encontrado</h4>
                        <p>Você ainda não fez nenhum pedido.</p>
                        <a href="/" class="btn btn-primary">Começar a comprar</a>
                    </div>
                <?php else: ?>
                    <div class="mt-4">
                        <?php foreach ($pedidos as $pedido): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <h6 class="card-title mb-1">Pedido</h6>
                                            <p class="mb-0 fw-bold"><?= htmlspecialchars($pedido['numero']) ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <h6 class="mb-1 text-muted">Data do Pedido</h6>
                                            <p class="mb-0"><?= $pedido['data_pedido'] ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <h6 class="mb-1 text-muted">Status</h6>
                                            <span class="badge bg-<?= $pedido['status_cor'] ?> fs-6">
                                                <?= $pedido['status_texto'] ?>
                                            </span>
                                        </div>
                                        <div class="col-md-2">
                                            <h6 class="mb-1 text-muted">Valor Total</h6>
                                            <p class="mb-0 fw-bold text-success">R$ <?= $pedido['valor_total'] ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <h6 class="mb-1 text-muted">Data Entrega</h6>
                                            <p class="mb-0"><?= $pedido['data_entrega'] ?></p>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <a href="/pedido-detalhes.php?id=<?= $pedido['id'] ?>" 
                                               class="btn btn-primary">Ver Detalhes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

