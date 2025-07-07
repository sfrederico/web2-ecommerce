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
                <form method="GET" action="gestao-pedidos.php" id="formBusca" class="row g-3">
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Pesquisar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btnLimpar">
                            <i class="fas fa-times"></i> Limpar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        
        <!-- Área de conteúdo dinâmico -->
        <div id="conteudo-pedidos">
            <?php if (isset($pedidos) && isset($paginacao)): ?>
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
                    
                    <div class="mt-4" id="lista-pedidos">
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
                                                <button class="btn btn-outline-primary btn-sm btn-detalhes" type="button" 
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
                        <div class="d-flex justify-content-center mt-4" id="paginacao">
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
            <?php else: ?>
                <div class="alert alert-info">
                    <h4>Carregando pedidos...</h4>
                    <p>Aguarde enquanto carregamos os dados.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Loading indicator -->
        <div id="loading-indicator" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2 text-muted">Carregando pedidos...</p>
        </div>
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
            buscarPedidos(1);
        });
        
        // Interceptar envio do formulário de busca
        $('#formBusca').on('submit', function(e) {
            e.preventDefault();
            buscarPedidos(1);
        });
        
        // Botão limpar busca
        $('#btnLimpar').on('click', function() {
            $('#busca_numero').val('');
            $('#per_page').val('2');
            buscarPedidos(1);
        });
        
        // Interceptar cliques na paginação
        $(document).on('click', '#paginacao .pagination a', function(e) {
            e.preventDefault();
            const url = new URL(this.href);
            const page = url.searchParams.get('page');
            buscarPedidos(page);
        });
        
        // Função principal para buscar pedidos via AJAX
        function buscarPedidos(page = 1) {
            const buscaNumero = $('#busca_numero').val();
            const perPage = $('#per_page').val();
            
            // Atualizar URL sem recarregar página
            const params = new URLSearchParams();
            if (buscaNumero) params.set('busca_numero', buscaNumero);
            if (perPage !== '2') params.set('per_page', perPage);
            if (page > 1) params.set('page', page);
            
            const newUrl = 'gestao-pedidos.php' + (params.toString() ? '?' + params.toString() : '');
            window.history.pushState({}, '', newUrl);
            
            // Mostrar loading
            $('#conteudo-pedidos').hide();
            $('#loading-indicator').show();
            
            // Fazer requisição AJAX
            $.ajax({
                url: 'gestao-pedidos.php',
                method: 'GET',
                data: {
                    busca_numero: buscaNumero,
                    per_page: perPage,
                    page: page
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                dataType: 'json'
            })
            .done(function(response) {
                if (response.success) {
                    atualizarConteudo(response.data);
                } else {
                    showErrorAlert('Erro ao carregar pedidos');
                }
            })
            .fail(function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
                showErrorAlert('Erro ao carregar pedidos. Tente novamente.');
            })
            .always(function() {
                $('#loading-indicator').hide();
                $('#conteudo-pedidos').show();
            });
        }
        
        // Função para atualizar o conteúdo da página
        function atualizarConteudo(data) {
            const { pedidos, paginacao, busca_numero } = data;
            
            let html = '';
            
            // Verificar se há pedidos
            if (pedidos.length === 0) {
                if (busca_numero) {
                    html = `
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Nenhum resultado encontrado</h5>
                            <p>Não foram encontrados pedidos com o termo "<strong>${escapeHtml(busca_numero)}</strong>" no número do pedido ou nome do cliente.</p>
                            <button class="btn btn-sm btn-outline-primary" onclick="$('#busca_numero').val(''); buscarPedidos(1);">Ver todos os pedidos</button>
                        </div>
                    `;
                } else {
                    html = `
                        <div class="alert alert-warning mt-4">
                            <h4>Nenhum pedido encontrado</h4>
                            <p>Não há pedidos confirmados com seus produtos.</p>
                        </div>
                    `;
                }
            } else {
                // Informações da paginação
                html += `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            ${busca_numero ? `
                                <div class="alert alert-success mb-0 py-2">
                                    <small><i class="fas fa-check-circle"></i> Resultados da busca: <strong>${paginacao.total}</strong> pedido(s) encontrado(s) com "<strong>${escapeHtml(busca_numero)}</strong>"</small>
                                </div>
                            ` : ''}
                        </div>
                        <div class="text-muted">
                            <small>Mostrando <strong>${paginacao.inicio}</strong> a <strong>${paginacao.fim}</strong> de <strong>${paginacao.total}</strong> pedido(s)</small>
                        </div>
                    </div>
                `;
                
                // Lista de pedidos
                html += '<div class="mt-4" id="lista-pedidos">';
                
                pedidos.forEach(pedido => {
                    html += `
                        <div class="card mb-3 pedido-row" data-pedido-id="${pedido.id}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <h6 class="card-title mb-1">Pedido</h6>
                                        <p class="mb-0 fw-bold">${escapeHtml(pedido.numero)}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6 class="mb-1 text-muted">Data do Pedido</h6>
                                        <p class="mb-0">${pedido.data_pedido}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6 class="mb-1 text-muted">Status</h6>
                                        <span class="badge bg-${pedido.status_class} fs-6">
                                            ${pedido.status_texto}
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <h6 class="mb-1 text-muted">Valor Total</h6>
                                        <p class="mb-0 fw-bold text-success">R$ ${pedido.valor_total}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6 class="mb-1 text-muted">Cliente</h6>
                                        <p class="mb-0">${escapeHtml(pedido.cliente_nome)}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-grid">
                                            <button class="btn btn-outline-primary btn-sm btn-detalhes" type="button" 
                                                    data-bs-toggle="modal" data-bs-target="#detalhesModal" 
                                                    data-pedido-id="${pedido.id}">
                                                Ver Detalhes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                
                // Paginação
                if (paginacao.total_paginas > 1) {
                    html += gerarPaginacao(paginacao);
                }
            }
            
            $('#conteudo-pedidos').html(html);
        }
        
        // Função para gerar HTML da paginação
        function gerarPaginacao(paginacao) {
            let html = '<div class="d-flex justify-content-center mt-4" id="paginacao"><nav aria-label="Navegação da paginação"><ul class="pagination">';
            
            // Botão Anterior
            if (paginacao.pagina_atual > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${paginacao.pagina_atual - 1}" aria-label="Anterior"><span aria-hidden="true">&laquo;</span></a></li>`;
            } else {
                html += '<li class="page-item disabled"><span class="page-link" aria-label="Anterior"><span aria-hidden="true">&laquo;</span></span></li>';
            }
            
            // Números das páginas
            const inicio = Math.max(1, paginacao.pagina_atual - 2);
            const fim = Math.min(paginacao.total_paginas, paginacao.pagina_atual + 2);
            
            for (let i = inicio; i <= fim; i++) {
                const activeClass = i === paginacao.pagina_atual ? 'active' : '';
                html += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            
            // Botão Próximo
            if (paginacao.pagina_atual < paginacao.total_paginas) {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${paginacao.pagina_atual + 1}" aria-label="Próximo"><span aria-hidden="true">&raquo;</span></a></li>`;
            } else {
                html += '<li class="page-item disabled"><span class="page-link" aria-label="Próximo"><span aria-hidden="true">&raquo;</span></span></li>';
            }
            
            html += '</ul></nav></div>';
            return html;
        }
        
        // Interceptar cliques nos links de paginação gerados dinamicamente
        $(document).on('click', '#paginacao .page-link[data-page]', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            buscarPedidos(page);
        });
        
        // Função para escapar HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Função para mostrar alerta de erro
        function showErrorAlert(message) {
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('#conteudo-pedidos').html(alertHtml);
        }
    </script>
</body>
</html>
