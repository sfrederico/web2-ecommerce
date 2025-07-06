<?php

require_once __DIR__ . '/../dao/PedidoDao.php';
require_once __DIR__ . '/../dao/ItemPedidoDao.php';
require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../dao/ClienteDao.php';
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../model/Pedido.php';

class GestaoPedidosService {
    private PedidoDao $pedidoDao;
    private ItemPedidoDao $itemPedidoDao;
    private ProdutoDao $produtoDao;
    private ClienteDao $clienteDao;
    private UsuarioDao $usuarioDao;

    public function __construct($dbConnection) {
        $this->pedidoDao = new PedidoDao($dbConnection);
        $this->itemPedidoDao = new ItemPedidoDao($dbConnection);
        $this->produtoDao = new ProdutoDao($dbConnection);
        $this->usuarioDao = new UsuarioDao($dbConnection);
        $this->clienteDao = new ClienteDao($dbConnection, $this->usuarioDao);
    }

    public function buscarPedidosPorFornecedor(int $fornecedorId): array {
        return $this->pedidoDao->getPedidosPorFornecedor($fornecedorId);
    }

    public function getDetalhesPedido(int $pedidoId): string {
        // Buscar o pedido
        $pedido = $this->pedidoDao->getPedidoById($pedidoId);
        
        if (!$pedido) {
            return '<div class="alert alert-danger">Pedido não encontrado.</div>';
        }
        
        // Buscar informações do cliente
        $cliente = $this->clienteDao->getClienteById($pedido->getClienteId());
        if ($cliente) {
            $pedido->setCliente($cliente);
        }
        
        // Buscar itens do pedido
        $itens = $this->itemPedidoDao->getItensByPedidoId($pedidoId);
        
        // Para cada item, buscar os detalhes do produto
        foreach ($itens as $item) {
            $produto = $this->produtoDao->getProdutoById($item->getProdutoId());
            if ($produto) {
                $item->setProduto($produto);
            }
        }
        
        $pedido->setItens($itens);
        
        // Renderizar HTML dos detalhes
        return $this->renderDetalhesModal($pedido);
    }
    
    private function renderDetalhesModal($pedido): string {
        ob_start();
        include __DIR__ . '/../views/gestao-pedidos/detalhes-modal.php';
        return ob_get_clean();
    }
    
    public function pedidoPertenceAoFornecedor(int $pedidoId, int $fornecedorId): bool {
        return $this->pedidoDao->verificarPedidoPertenceAoFornecedor($pedidoId, $fornecedorId);
    }
    
    public function atualizarPedido(int $pedidoId, string $novoStatus, ?string $novaDataEntrega): bool {
        // Validar status
        $statusPermitidos = ['PENDENTE', 'PROCESSANDO', 'ENVIADO', 'ENTREGUE', 'CANCELADO'];
        if (!in_array($novoStatus, $statusPermitidos)) {
            throw new Exception('Status inválido.');
        }
        
        // Validar data de entrega (se fornecida)
        if ($novaDataEntrega && !empty($novaDataEntrega)) {
            $dataEntrega = date_create_from_format('Y-m-d', $novaDataEntrega);
            $hoje = date_create('today');
            if (!$dataEntrega || $dataEntrega < $hoje) {
                throw new Exception('Data de entrega inválida ou no passado.');
            }
        }
        
        // Atualizar o pedido
        return $this->pedidoDao->atualizarStatusEDataEntrega($pedidoId, $novoStatus, $novaDataEntrega);
    }
}
