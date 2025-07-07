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
        
        $pedidos = $resultado['pedidos'];
        $paginacao = $resultado;
        
        include_once(__DIR__ . '/../views/gestao-pedidos/painel.php');
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
