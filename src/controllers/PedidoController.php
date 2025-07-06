<?php

require_once __DIR__ . '/../services/PedidoService.php';

class PedidoController {
    private PedidoService $pedidoService;
    
    public function __construct(private $dbConnection) {
        $this->pedidoService = new PedidoService($dbConnection);
    }

    public function listarMeusPedidos() {
        $clienteId = $_SESSION['user']['id'];
        
        try {
            $pedidos = $this->pedidoService->getPedidosConfirmadosDoCliente($clienteId);
            
            include __DIR__ . '/../views/pedidos/meus-pedidos.php';
            
        } catch (Exception $e) {
            $_SESSION['erro'] = "Erro ao carregar pedidos: " . $e->getMessage();
            $pedidos = [];
            include __DIR__ . '/../views/pedidos/meus-pedidos.php';
        }
    }

    public function getDetalhesPedido(int $pedidoId) {
        $clienteId = $_SESSION['user']['id'];
        
        try {
            $detalhes = $this->pedidoService->getDetalhesPedido($clienteId, $pedidoId);
            
            echo $detalhes;

        } catch (Exception $e) {
            echo "Erro ao carregar detalhes: " . $e->getMessage();
        }
    }

    public function confirmarPedido() {
        $clienteId = $_SESSION['user']['id'];
        
        try {
            $resultado = $this->pedidoService->confirmarPedido($clienteId);
            
            if ($resultado['sucesso']) {
                $_SESSION['sucesso'] = $resultado['mensagem'];
            } else {
                $_SESSION['erro'] = $resultado['mensagem'];
                $_SESSION['itens_sem_estoque'] = $resultado['itens_sem_estoque'];
            }
            header('Location: /carrinho.php');
            exit;

        } catch (Exception $e) {
            $_SESSION['erro'] = 'Erro ao confirmar pedido: ' . $e->getMessage();
            header('Location: /carrinho.php');
            exit;
        }
    }
}
