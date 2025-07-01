<?php

require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/ItemPedido.php';
require_once __DIR__ . '/../dao/PedidoDao.php';
require_once __DIR__ . '/../dao/ProdutoDao.php';


class CarrinhoService {

    private PedidoDao $pedidoDao;
    private ProdutoDao $produtoDao;

    public function __construct(private $dbConnection) {
        $this->pedidoDao = new PedidoDao($dbConnection);
        $this->produtoDao = new ProdutoDao($dbConnection);
    }


    
    public function adicionarProduto(int $clienteId, int $produtoId): void {
        $produto = $this->produtoDao->getProdutoById($produtoId);
        if (!$produto) {
            throw new Exception("Produto não encontrado.");
        }

        $pedido = $this->pedidoDao->getPedidoNaoConfirmado($clienteId);
        if (!$pedido) {
            // Gerar número único para o pedido
            $numeroPedido = 'PED-' . date('YmdHis') . '-' . $clienteId;
            
            // Instanciar objeto Pedido
            $pedido = new Pedido($numeroPedido, $clienteId);

            // Persistir o pedido no banco
            $pedidoId = $this->pedidoDao->create($pedido);
            $pedido->setId($pedidoId);
        }
        
        // Instanciar objeto ItemPedido
        // $itemPedido = new ItemPedido($produtoId, $quantidade, $precoUnitario); // pedidoId será 0 por enquanto
        
        // Por enquanto apenas criamos os objetos
        // Futuramente aqui chamaremos os DAOs para persistir no banco
        
        echo "Produto adicionado ao carrinho!";
        echo "<br>Pedido: " . $pedido->getNumero();
        echo "<br>Cliente ID: " . $pedido->getClienteId();
        // echo "<br>Item: Produto ID " . $itemPedido->getProdutoId() . 
        //      " - Quantidade: " . $itemPedido->getQuantidade() . 
        //      " - Subtotal: R$ " . number_format($itemPedido->getSubtotal(), 2, ',', '.');
    }
}