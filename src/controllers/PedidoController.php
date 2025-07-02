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
}
