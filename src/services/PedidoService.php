<?php

require_once __DIR__ . '/../dao/PedidoDao.php';

class PedidoService {
    private PedidoDao $pedidoDao;

    public function __construct(private $dbConnection) {
        $this->pedidoDao = new PedidoDao($dbConnection);
    }

    public function getPedidosConfirmadosDoCliente(int $clienteId): array {
        $pedidos = $this->pedidoDao->getPedidosByClienteId($clienteId);
        
        // Processar dados dos pedidos para a view
        $pedidosProcessados = [];
        foreach ($pedidos as $pedido) {
            $statusInfo = $this->getStatusInfo($pedido->getSituacao());
            
            $pedidosProcessados[] = [
                'id' => $pedido->getId(),
                'numero' => $pedido->getNumero(),
                'data_pedido' => date('d/m/Y H:i', strtotime($pedido->getDataPedido())),
                'data_entrega' => $pedido->getDataEntrega() ? date('d/m/Y', strtotime($pedido->getDataEntrega())) : '-',
                'situacao' => $pedido->getSituacao(),
                'status_cor' => $statusInfo['cor'],
                'status_texto' => $statusInfo['texto'],
                'valor_total' => number_format($pedido->getSubtotal(), 2, ',', '.'),
                'confirmado' => $pedido->isConfirmado()
            ];
        }
        
        return $pedidosProcessados;
    }

    public function getStatusInfo(string $situacao): array {
        $statusMap = [
            'PENDENTE' => ['cor' => 'warning', 'texto' => 'Pendente'],
            'CONFIRMADO' => ['cor' => 'info', 'texto' => 'Confirmado'],
            'PROCESSANDO' => ['cor' => 'primary', 'texto' => 'Processando'],
            'ENVIADO' => ['cor' => 'secondary', 'texto' => 'Enviado'],
            'ENTREGUE' => ['cor' => 'success', 'texto' => 'Entregue'],
            'CANCELADO' => ['cor' => 'danger', 'texto' => 'Cancelado']
        ];

        return $statusMap[$situacao] ?? ['cor' => 'secondary', 'texto' => $situacao];
    }
}
