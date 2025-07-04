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
                                                    'NOVO' => 'primary',
                                                    'ENVIADO' => 'warning',
                                                    'ENTREGUE' => 'success',
                                                    'CANCELADO' => 'danger',
                                                    default => 'secondary'
                                                };
                                                $statusTexto = match($situacao) {
                                                    'NOVO' => 'Novo',
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
                                            <h6 class="mb-1 text-muted">Data Entrega</h6>
                                            <p class="mb-0"><?= $pedido->getDataEntrega() ? date('d/m/Y', strtotime($pedido->getDataEntrega())) : '-' ?></p>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button type="button" class="btn btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDetalhes"
                                                    data-pedido-id="<?= $pedido->getId() ?>">
                                                Ver Detalhes
                                            </button>
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

    <!-- Modal de Detalhes -->
    <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalhesLabel">Detalhes do Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal totalmente em branco por enquanto -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalDetalhes');
        const modalBody = modal.querySelector('.modal-body');
        
        modal.addEventListener('show.bs.modal', function (event) {
            // Botão que acionou o modal
            const button = event.relatedTarget;
            
            // Pegar o ID do pedido do data-attribute
            const pedidoId = button.getAttribute('data-pedido-id');
            
            // Mostrar loading
            modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
            
            // Fazer requisição AJAX
            fetch(`/meus-pedidos.php?action=detalhes&pedido_id=${pedidoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.text();
                })
                .then(data => {
                    // Colocar o resultado no modal
                    modalBody.innerHTML = data;
                })
                .catch(error => {
                    console.error('Erro:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger">Erro ao carregar detalhes do pedido.</div>';
                });
        });
    });
    </script>

</body>
</html>

