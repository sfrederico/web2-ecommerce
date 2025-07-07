<?php

require_once __DIR__ . '/../services/GestaoPedidosService.php';

class GestaoPedidosController {
    private $gestaoPedidosService;

    public function __construct($dbConnection) {
        $this->gestaoPedidosService = new GestaoPedidosService($dbConnection);
    }

    public function mostrarPainelGestao() {
        $fornecedorId = $_SESSION['user']['id'];
        $buscaNumero = $_GET['busca_numero'] ?? null;
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = max(1, intval($_GET['per_page'] ?? 2));

        if ($buscaNumero && !empty(trim($buscaNumero))) {
            $resultado = $this->gestaoPedidosService->buscarPedidosPorFornecedorETermoPaginado($fornecedorId, trim($buscaNumero), $page, $perPage);
        } else {
            $resultado = $this->gestaoPedidosService->buscarPedidosPorFornecedorPaginado($fornecedorId, $page, $perPage);
        }
        
        // Verificar se é uma requisição AJAX
        if ($this->isAjaxRequest()) {
            $this->retornarJsonResponse($resultado, $buscaNumero);
            return;
        }
        
        $pedidos = $resultado['pedidos'];
        $paginacao = $resultado;
        
        include_once(__DIR__ . '/../views/gestao-pedidos/painel.php');
    }

    private function isAjaxRequest(): bool {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function retornarJsonResponse(array $resultado, ?string $buscaNumero): void {
        header('Content-Type: application/json');
        
        $response = [
            'success' => true,
            'data' => [
                'pedidos' => $this->formatarPedidosParaJson($resultado['pedidos']),
                'paginacao' => [
                    'pagina_atual' => $resultado['pagina_atual'],
                    'total_paginas' => $resultado['total_paginas'],
                    'total' => $resultado['total'],
                    'por_pagina' => $resultado['por_pagina'],
                    'inicio' => $resultado['inicio'],
                    'fim' => $resultado['fim']
                ],
                'busca_numero' => $buscaNumero
            ]
        ];
        
        echo json_encode($response);
        exit;
    }

    private function formatarPedidosParaJson(array $pedidos): array {
        $pedidosFormatados = [];
        
        foreach ($pedidos as $pedido) {
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
            
            $pedidosFormatados[] = [
                'id' => $pedido->getId(),
                'numero' => $pedido->getNumero(),
                'data_pedido' => date('d/m/Y H:i', strtotime($pedido->getDataPedido())),
                'situacao' => $situacao,
                'status_class' => $statusClass,
                'status_texto' => $statusTexto,
                'valor_total' => number_format($pedido->getValorTotal(), 2, ',', '.'),
                'cliente_nome' => $pedido->getCliente()->getUsuario()->getNome()
            ];
        }
        
        return $pedidosFormatados;
    }

    public function getDetalhesPedido(int $pedidoId) {
        $detalhes = $this->gestaoPedidosService->getDetalhesPedido($pedidoId);
        echo $detalhes;
    }

    public function atualizarPedido() {
        try {
            // Validação dos dados recebidos
            $pedidoId = $_POST['pedido_id'] ?? null;
            $novoStatus = $_POST['status'] ?? null;
            $novaDataEntrega = $_POST['data_entrega'] ?? null;
            
            if (!$pedidoId || !$novoStatus) {
                throw new Exception('Dados obrigatórios não foram fornecidos.');
            }
            
            // Validar se o pedido pertence ao fornecedor logado
            $fornecedorId = $_SESSION['user']['id'];
            if (!$this->gestaoPedidosService->pedidoPertenceAoFornecedor($pedidoId, $fornecedorId)) {
                throw new Exception('Você não tem permissão para atualizar este pedido.');
            }
            
            // Atualizar o pedido
            $resultado = $this->gestaoPedidosService->atualizarPedido($pedidoId, $novoStatus, $novaDataEntrega);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Pedido atualizado com sucesso!'
                ]);
            } else {
                throw new Exception('Erro ao atualizar o pedido.');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
