<?php

require_once __DIR__ . '/../dao/PedidoDao.php';
require_once __DIR__ . '/../dao/ItemPedidoDao.php';
require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/ItemPedido.php';

class PedidoService {
    private PedidoDao $pedidoDao;
    private ItemPedidoDao $itemPedidoDao;

    public function __construct(private $dbConnection) {
        $this->pedidoDao = new PedidoDao($dbConnection);
        $this->itemPedidoDao = new ItemPedidoDao($dbConnection);
    }

    public function getPedidosConfirmadosDoCliente(int $clienteId): array {
        return $this->pedidoDao->getPedidosByClienteId($clienteId);
    }

    public function renderDetalhesModal(Pedido $pedido): string {
        // Iniciar output buffering para capturar o HTML
        ob_start();
        
        // Incluir o template (as variáveis $pedido e $itens estarão disponíveis)
        include __DIR__ . '/../views/pedidos/detalhes-modal.php';
        
        // Capturar o HTML renderizado como string
        $html = ob_get_clean();
        
        return $html;
    }

    public function getDetalhesPedido(int $clienteId, int $pedidoId): string {
        // Aqui você pode buscar os detalhes do pedido, como itens, total, etc.
        $pedido = $this->pedidoDao->getPedidoById($pedidoId);
        
        if (!$pedido || $pedido->getClienteId() !== $clienteId) {
            throw new Exception("Pedido não encontrado ou não pertence ao cliente.");
        }

        $itens = $this->itemPedidoDao->getItensByPedidoId($pedidoId);

        $pedido->setItens($itens);

        return $this->renderDetalhesModal($pedido);
    }

    public function confirmarPedido($clienteId): bool
    {   
        $pedido = $this->pedidoDao->getPedidoNaoConfirmado($clienteId);

        $itens = $this->itemPedidoDao->getItensByPedidoId($pedido->getId());

        if (empty($itens)) {
            throw new Exception("Carrinho vazio, não é possível confirmar o pedido.");
        }

        // TODO : Implementar lógica de validação de quantidade em estoque

        


        echo "Confirmar pedido para o cliente: " . $clienteId;

    }

}
