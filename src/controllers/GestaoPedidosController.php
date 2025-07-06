<?php

require_once __DIR__ . '/../services/GestaoPedidosService.php';

class GestaoPedidosController {
    private $gestaoPedidosService;

    public function __construct($dbConnection) {
        $this->gestaoPedidosService = new GestaoPedidosService($dbConnection);
    }

    public function mostrarPainelGestao() {
        $fornecedorId = $_SESSION['user']['id'];

        $pedidos = $this->gestaoPedidosService->buscarPedidosPorFornecedor($fornecedorId);
        
        include_once(__DIR__ . '/../views/gestao-pedidos/painel.php');
    }

    public function getDetalhesPedido(int $pedidoId) {
        $detalhes = $this->gestaoPedidosService->getDetalhesPedido($pedidoId);
        echo $detalhes;
    }
}
