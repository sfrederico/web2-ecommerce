<?php

require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/ItemPedido.php';

class CarrinhoService {

    public function __construct(private $dbConnection) {
        // Aqui você pode inicializar conexões com o banco de dados, se necessário
    }


    
    public function adicionarProduto(int $clienteId, int $produtoId): void {
        // Gerar número único para o pedido
        $numeroPedido = 'PED-' . date('YmdHis') . '-' . $clienteId;
        
        // Instanciar objeto Pedido
        $pedido = new Pedido($numeroPedido, $clienteId);
        
        // Instanciar objeto ItemPedido
        // $itemPedido = new ItemPedido(0, $produtoId, $quantidade, $precoUnitario); // pedidoId será 0 por enquanto
        
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