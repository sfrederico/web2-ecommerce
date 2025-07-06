<div class="row mb-3">
    <div class="col-md-6">
        <h6><strong>Número do Pedido:</strong> <?= htmlspecialchars($pedido->getNumero()) ?></h6>
    </div>
    <div class="col-md-6">
        <h6><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedido->getDataPedido())) ?></h6>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h6><strong>Status:</strong> 
            <span class="badge bg-primary status-badge"><?= htmlspecialchars($pedido->getSituacao()) ?></span>
        </h6>
    </div>
    <div class="col-md-6">
        <h6><strong>Valor Total:</strong> 
            <span class="text-success fw-bold">R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.') ?></span>
        </h6>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h6><strong>Data de Entrega:</strong> 
            <?= $pedido->getDataEntrega() ? date('d/m/Y', strtotime($pedido->getDataEntrega())) : '<span class="text-muted">Não definida</span>' ?>
        </h6>
    </div>
    <div class="col-md-6">
        <!-- Espaço reservado -->
    </div>
</div>

<!-- Seção de Atualização do Pedido -->
<div class="card mb-3">
    <div class="card-header bg-light">
        <h6 class="mb-0"><strong>Atualizar Pedido</strong></h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status-pedido" class="form-label"><strong>Alterar Status:</strong></label>
                    <select class="form-select status-pedido" 
                            id="status-pedido"
                            data-pedido-id="<?= $pedido->getId() ?>">
                        <option value="PENDENTE" <?= $pedido->getSituacao() === 'PENDENTE' ? 'selected' : '' ?>>Pendente</option>
                        <option value="PROCESSANDO" <?= $pedido->getSituacao() === 'PROCESSANDO' ? 'selected' : '' ?>>Processando</option>
                        <option value="ENVIADO" <?= $pedido->getSituacao() === 'ENVIADO' ? 'selected' : '' ?>>Enviado</option>
                        <option value="ENTREGUE" <?= $pedido->getSituacao() === 'ENTREGUE' ? 'selected' : '' ?>>Entregue</option>
                        <option value="CANCELADO" <?= $pedido->getSituacao() === 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="data-entrega" class="form-label"><strong>Data de Entrega:</strong></label>
                    <input type="date" 
                           class="form-control data-entrega" 
                           id="data-entrega"
                           data-pedido-id="<?= $pedido->getId() ?>"
                           value="<?= $pedido->getDataEntrega() ? date('Y-m-d', strtotime($pedido->getDataEntrega())) : '' ?>"
                           min="<?= date('Y-m-d') ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-success btn-sm atualizar-pedido" data-pedido-id="<?= $pedido->getId() ?>">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <small class="text-muted ms-2">As alterações serão aplicadas imediatamente.</small>
            </div>
        </div>
    </div>
</div>

<!-- Informações do Cliente -->
<?php if ($pedido->getCliente()): ?>
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0"><strong>Informações do Cliente</strong></h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nome:</strong> <?= htmlspecialchars($pedido->getCliente()->getUsuario()->getNome()) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($pedido->getCliente()->getUsuario()->getEmail()) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Telefone:</strong> <?= htmlspecialchars($pedido->getCliente()->getUsuario()->getTelefone()) ?></p>
                <p><strong>Usuário:</strong> <?= htmlspecialchars($pedido->getCliente()->getUsuario()->getNomeUsuario()) ?></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if (!empty($pedido->getItens())): ?>
    <h6 class="mb-3"><strong>Itens do Pedido:</strong></h6>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Produto</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Preço Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedido->getItens() as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item->getProduto()->getFoto()): ?>
                                <img src="<?= htmlspecialchars($item->getProduto()->getFoto()) ?>" 
                                     alt="Foto do produto" 
                                     class="img-thumbnail" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; border-radius: 4px;">
                                    <small class="text-muted">Sem foto</small>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item->getProduto()->getNome()) ?></td>
                        <td><?= htmlspecialchars($item->getProduto()->getDescricao()) ?></td>
                        <td><?= $item->getQuantidade() ?></td>
                        <td>R$ <?= number_format($item->getPrecoUnitario(), 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item->getSubtotal(), 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Nenhum item encontrado para este pedido.</div>
<?php endif; ?>
