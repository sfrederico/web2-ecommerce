<!-- Template para detalhes do pedido no modal -->
<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="text-muted mb-1">Número do Pedido</h6>
        <p class="fw-bold mb-0"><?= htmlspecialchars($pedido->getNumero()) ?></p>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted mb-1">Data do Pedido</h6>
        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($pedido->getDataPedido())) ?></p>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="text-muted mb-1">Status</h6>
        <?php 
            $situacao = $pedido->getSituacao();
            $statusClass = match($situacao) {
                'PENDENTE' => 'warning',
                'CONFIRMADO' => 'info',
                'PROCESSANDO' => 'primary',
                'ENVIADO' => 'secondary',
                'ENTREGUE' => 'success',
                'CANCELADO' => 'danger',
                default => 'secondary'
            };
            $statusTexto = match($situacao) {
                'PENDENTE' => 'Pendente',
                'CONFIRMADO' => 'Confirmado',
                'PROCESSANDO' => 'Processando',
                'ENVIADO' => 'Enviado',
                'ENTREGUE' => 'Entregue',
                'CANCELADO' => 'Cancelado',
                default => $situacao
            };
        ?>
        <span class="badge bg-<?= $statusClass ?> fs-6"><?= $statusTexto ?></span>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted mb-1">Valor Total</h6>
        <p class="fw-bold text-success mb-0">R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.') ?></p>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="text-muted mb-1">Data de Entrega</h6>
        <p class="mb-0"><?= $pedido->getDataEntrega() ? date('d/m/Y', strtotime($pedido->getDataEntrega())) : 'Não definida' ?></p>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted mb-1">Confirmado</h6>
        <span class="badge bg-<?= $pedido->isConfirmado() ? 'success' : 'warning' ?>">
            <?= $pedido->isConfirmado() ? 'Sim' : 'Não' ?>
        </span>
    </div>
</div>

<hr>

<h6 class="mb-3">Itens do Pedido</h6>

<?php if (!empty($pedido->getItens())): ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead class="table-light">
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedido->getItens() as $item): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if ($item->getProduto()->getFoto()): ?>
                                    <img src="<?= htmlspecialchars($item->getProduto()->getFoto()) ?>" 
                                         alt="<?= htmlspecialchars($item->getProduto()->getNome()) ?>" 
                                         class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                <?php endif; ?>
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($item->getProduto()->getNome()) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($item->getProduto()->getDescricao()) ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">
                            <?= $item->getQuantidade() ?>
                        </td>
                        <td class="align-middle">
                            R$ <?= number_format($item->getPrecoUnitario(), 2, ',', '.') ?>
                        </td>
                        <td class="align-middle">
                            <strong>R$ <?= number_format($item->getSubtotal(), 2, ',', '.') ?></strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Total do Pedido:</th>
                    <th>R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Nenhum item encontrado para este pedido.
    </div>
<?php endif; ?>
