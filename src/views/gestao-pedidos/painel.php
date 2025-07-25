<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Pedidos - Fornecedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    
    <?php
    // Função para construir URLs de paginação mantendo os parâmetros de busca
    function construirUrlPaginacao($pagina) {
        $params = $_GET;
        $params['page'] = $pagina;
        return 'gestao-pedidos.php?' . http_build_query($params);
    }
    ?>
    
    <div class="container my-5">
        <h1 class="text-center mb-4">Gestão de Pedidos</h1>
        
        <!-- Formulário de Busca -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="gestao-pedidos.php" class="row g-3">
                    <div class="col-md-6">
                        <label for="busca_numero" class="form-label">Buscar por Número do Pedido ou Nome do Cliente:</label>
                        <input type="text" 
                               class="form-control" 
                               id="busca_numero" 
                               name="busca_numero" 
                               placeholder="Digite o número do pedido ou nome do cliente..."
                               value="<?= htmlspecialchars($_GET['busca_numero'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="per_page" class="form-label">Itens por página:</label>
                        <select class="form-select" id="per_page" name="per_page">
                            <option value="2" <?= ($_GET['per_page'] ?? 2) == 2 ? 'selected' : '' ?>>2 por página</option>
                            <option value="5" <?= ($_GET['per_page'] ?? 2) == 5 ? 'selected' : '' ?>>5 por página</option>
                            <option value="10" <?= ($_GET['per_page'] ?? 2) == 10 ? 'selected' : '' ?>>10 por página</option>
                            <option value="20" <?= ($_GET['per_page'] ?? 2) == 20 ? 'selected' : '' ?>>20 por página</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2 justify-content-end">
                        <button type="submit" class="btn rounded-pill" style="background-color: #4d41d3; color: white; border-color: #4d41d3;">
                            <i class="fas fa-search"></i> Pesquisar
                        </button>
                        <a href="gestao-pedidos.php" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if (isset($_GET['busca_numero']) && !empty($_GET['busca_numero']) && empty($pedidos)): ?>
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Nenhum resultado encontrado</h5>
                <p>Não foram encontrados pedidos com o termo "<strong><?= htmlspecialchars($_GET['busca_numero']) ?></strong>" no número do pedido ou nome do cliente.</p>
                <a href="gestao-pedidos.php" class="btn btn-sm btn-outline-primary">Ver todos os pedidos</a>
            </div>
        <?php elseif (empty($pedidos)): ?>
            <div class="alert alert-warning mt-4">
                <h4>Nenhum pedido encontrado</h4>
                <p>Não há pedidos confirmados com seus produtos.</p>
            </div>
        <?php else: ?>
            <!-- Informações da Paginação -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <?php if (isset($_GET['busca_numero']) && !empty($_GET['busca_numero'])): ?>
                        <div class="alert alert-success mb-0 py-2">
                            <small><i class="fas fa-check-circle"></i> Resultados da busca: <strong><?= $paginacao['total'] ?></strong> pedido(s) encontrado(s) com "<strong><?= htmlspecialchars($_GET['busca_numero']) ?></strong>"</small>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="text-muted">
                    <small>Mostrando <strong><?= $paginacao['inicio'] ?></strong> a <strong><?= $paginacao['fim'] ?></strong> de <strong><?= $paginacao['total'] ?></strong> pedido(s)</small>
                </div>
            </div>
            
            <div class="mt-4">
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="card mb-3 pedido-row" data-pedido-id="<?= $pedido->getId() ?>">
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
                                    <h6 class="mb-1 text-muted">Cliente</h6>
                                    <p class="mb-0"><?= htmlspecialchars($pedido->getCliente()->getUsuario()->getNome()) ?></p>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-grid">
                                        <button class="btn btn-sm rounded-pill btn-detalhes" type="button" 
                                                style="background-color: #4d41d3; color: white; border-color: #4d41d3;"
                                                data-bs-toggle="modal" data-bs-target="#detalhesModal" 
                                                data-pedido-id="<?= $pedido->getId() ?>">
                                            Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Paginação -->
            <?php if ($paginacao['total_paginas'] > 1): ?>
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Navegação da paginação">
                        <ul class="pagination">
                            <!-- Botão Anterior -->
                            <?php if ($paginacao['pagina_atual'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= construirUrlPaginacao($paginacao['pagina_atual'] - 1) ?>" aria-label="Anterior">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Anterior">
                                        <span aria-hidden="true">&laquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                            
                            <!-- Números das páginas -->
                            <?php
                            $inicio = max(1, $paginacao['pagina_atual'] - 2);
                            $fim = min($paginacao['total_paginas'], $paginacao['pagina_atual'] + 2);
                            
                            for ($i = $inicio; $i <= $fim; $i++):
                            ?>
                                <li class="page-item <?= $i == $paginacao['pagina_atual'] ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= construirUrlPaginacao($i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <!-- Botão Próximo -->
                            <?php if ($paginacao['pagina_atual'] < $paginacao['total_paginas']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= construirUrlPaginacao($paginacao['pagina_atual'] + 1) ?>" aria-label="Próximo">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Próximo">
                                        <span aria-hidden="true">&raquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <!-- Modal de Detalhes -->
    <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalhesModalLabel">Detalhes do Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="loading" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>
                    <div id="modal-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Adicionar event listener para os botões de detalhes
            document.querySelectorAll('.btn-detalhes').forEach(function(button) {
                button.addEventListener('click', function() {
                    const pedidoId = this.getAttribute('data-pedido-id');
                    carregarDetalhes(pedidoId);
                });
            });
        });

        function carregarDetalhes(pedidoId) {
            const loading = document.getElementById('loading');
            const modalContent = document.getElementById('modal-content');
            
            // Mostrar loading e limpar conteúdo
            loading.style.display = 'block';
            modalContent.innerHTML = '';
            
            // Fazer requisição AJAX
            fetch(`gestao-pedidos.php?action=detalhes&pedido_id=${pedidoId}`)
                .then(response => response.text())
                .then(data => {
                    // Esconder loading e mostrar conteúdo
                    loading.style.display = 'none';
                    modalContent.innerHTML = data;
                })
                .catch(error => {
                    // Esconder loading e mostrar erro
                    loading.style.display = 'none';
                    modalContent.innerHTML = '<div class="alert alert-danger">Erro ao carregar detalhes do pedido.</div>';
                    console.error('Erro:', error);
                });
        }
        
        // Delegação de eventos para botões carregados dinamicamente
        $(document).on('click', '.atualizar-pedido', function() {
            const pedidoId = $(this).data('pedido-id');
            const novoStatus = $('#status-pedido').val();
            const novaDataEntrega = $('#data-entrega').val();
            
            console.log('Dados:', { pedidoId, novoStatus, novaDataEntrega }); // Debug
            
            // Validação básica
            if (!pedidoId) {
                alert('Erro: ID do pedido não encontrado.');
                return;
            }
            
            // Confirmação antes de atualizar
            if (!confirm('Deseja realmente atualizar este pedido?')) {
                return;
            }
            
            // Desabilita o botão durante a requisição
            const $botao = $(this);
            const textoOriginal = $botao.html();
            $botao.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
            
            // Requisição AJAX
            $.ajax({
                url: 'gestao-pedidos.php',
                type: 'POST',
                data: {
                    action: 'atualizar',
                    pedido_id: pedidoId,
                    status: novoStatus,
                    data_entrega: novaDataEntrega
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Atualizar o badge de status na listagem principal
                        updateStatusBadge(pedidoId, novoStatus);
                        
                        // Feedback de sucesso
                        showAlert('success', 'Pedido atualizado com sucesso!');
                        
                        // Fechar o modal após 1 segundo
                        setTimeout(function() {
                            $('#detalhesModal').modal('hide');
                            // Recarregar a página para atualizar a lista
                            location.reload();
                        }, 1000);
                    } else {
                        showAlert('danger', response.message || 'Erro ao atualizar o pedido.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error);
                    showAlert('danger', 'Erro na comunicação com o servidor.');
                },
                complete: function() {
                    // Reabilita o botão
                    $botao.prop('disabled', false).html(textoOriginal);
                }
            });
        });
        
        // Função para atualizar o badge de status na listagem
        function updateStatusBadge(pedidoId, novoStatus) {
            const $badge = $(`.pedido-row[data-pedido-id="${pedidoId}"] .badge`);
            if ($badge.length) {
                // Remove classes de badge existentes
                $badge.removeClass('bg-secondary bg-primary bg-warning bg-success bg-danger');
                
                // Adiciona nova classe e texto baseado no status
                let badgeClass = 'bg-secondary';
                let badgeText = novoStatus;
                
                switch(novoStatus) {
                    case 'PENDENTE':
                        badgeClass = 'bg-secondary';
                        badgeText = 'Pendente';
                        break;
                    case 'PROCESSANDO':
                        badgeClass = 'bg-primary';
                        badgeText = 'Processando';
                        break;
                    case 'ENVIADO':
                        badgeClass = 'bg-warning';
                        badgeText = 'Enviado';
                        break;
                    case 'ENTREGUE':
                        badgeClass = 'bg-success';
                        badgeText = 'Entregue';
                        break;
                    case 'CANCELADO':
                        badgeClass = 'bg-danger';
                        badgeText = 'Cancelado';
                        break;
                }
                
                $badge.addClass(badgeClass).text(badgeText);
            }
        }
        
        // Função para exibir alertas
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            // Remove alertas anteriores
            $('.modal-body .alert').remove();
            
            // Adiciona o novo alerta no topo do modal
            $('.modal-body').prepend(alertHtml);
            
            // Remove o alerta após 5 segundos
            setTimeout(function() {
                $('.alert').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
        
        // Auto-envio do formulário quando mudar itens por página
        $('#per_page').on('change', function() {
            $(this).closest('form').submit();
        });
    </script>
</body>
</html>
