<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Pedidos - Fornecedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    
    <div class="container my-5">
        <h1 class="text-center mb-4">Gestão de Pedidos</h1>
        
        <?php if (empty($pedidos)): ?>
            <div class="alert alert-warning mt-4">
                <h4>Nenhum pedido encontrado</h4>
                <p>Não há pedidos confirmados com seus produtos.</p>
            </div>
        <?php else: ?>
            <div class="mt-4">
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <h6 class="card-title mb-1">Pedido</h6>
                                    <p class="mb-0 fw-bold"><?= htmlspecialchars($pedido->getNumero()) ?></p>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-1 text-muted">Data do Pedido</h6>
                                    <p class="mb-0"><?= date('d/m/Y H:i', strtotime($pedido->getDataPedido())) ?></p>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-1 text-muted">Status</h6>
                                    <?php 
                                        $situacao = $pedido->getSituacao();
                                        $statusClass = match($situacao) {
                                            'PENDENTE' => 'primary',
                                            'PROCESSANDO' => 'info',
                                            'ENVIADO' => 'warning',
                                            'ENTREGUE' => 'success',
                                            'CANCELADO' => 'danger',
                                            default => 'secondary'
                                        };
                                        $statusTexto = match($situacao) {
                                            'PENDENTE' => 'Pendente',
                                            'PROCESSANDO' => 'Processando',
                                            'ENVIADO' => 'Enviado',
                                            'ENTREGUE' => 'Entregue',
                                            'CANCELADO' => 'Cancelado',
                                            default => $situacao
                                        };
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?> fs-6">
                                        <?= $statusTexto ?>
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-1 text-muted">Valor Total</h6>
                                    <p class="mb-0 fw-bold text-success">R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.') ?></p>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-1 text-muted">Data de Entrega</h6>
                                    <p class="mb-0"><?= $pedido->getDataEntrega() ? date('d/m/Y', strtotime($pedido->getDataEntrega())) : '-' ?></p>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-grid">
                                        <button class="btn btn-outline-primary btn-sm" type="button">
                                            <i class="fas fa-eye"></i> Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
