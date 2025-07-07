<?php

require_once __DIR__ . '/../dao/PedidoDao.php';

class ApiController {
    private $dbConnection;
    
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }
    
    public function buscarPedidos($termo = null) {
        try {
            $pedidoDao = new PedidoDao($this->dbConnection);
            $pedidos = $pedidoDao->getTodosPedidos($termo);
            
            // Converter pedidos para array
            $pedidosArray = [];
            foreach ($pedidos as $pedido) {
                $pedidosArray[] = [
                    'id' => $pedido->getId(),
                    'numero' => $pedido->getNumero(),
                    'cliente_id' => $pedido->getClienteId(),
                    'cliente_nome' => $pedido->getCliente() ? $pedido->getCliente()->getUsuario()->getNome() : null,
                    'cliente_email' => $pedido->getCliente() ? $pedido->getCliente()->getUsuario()->getEmail() : null,
                    'data_pedido' => $pedido->getDataPedido(),
                    'data_entrega' => $pedido->getDataEntrega(),
                    'situacao' => $pedido->getSituacao(),
                    'confirmado' => $pedido->isConfirmado(),
                    'valor_total' => $pedido->getValorTotal()
                ];
            }
            
            return [
                'success' => true,
                'data' => $pedidosArray,
                'total' => count($pedidosArray),
                'termo_busca' => $termo,
                'timestamp' => date('Y-m-d H:i:s')
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao buscar pedidos: ' . $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
    }
}
