<?php

require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/ItemPedido.php';
require_once __DIR__ . '/../dao/EstoqueDao.php';
require_once __DIR__ . '/../dao/ItemPedidoDao.php';
require_once __DIR__ . '/../dao/PedidoDao.php';
require_once __DIR__ . '/../dao/ProdutoDao.php';


class CarrinhoService {

    private EstoqueDao $estoqueDao;
    private ItemPedidoDao $itemPedidoDao;
    private PedidoDao $pedidoDao;
    private ProdutoDao $produtoDao;

    public function __construct(private $dbConnection) {
        $this->estoqueDao = new EstoqueDao($dbConnection);
        $this->itemPedidoDao = new ItemPedidoDao($dbConnection);
        $this->pedidoDao = new PedidoDao($dbConnection);
        $this->produtoDao = new ProdutoDao($dbConnection);
    }


    
    public function adicionarProduto(int $clienteId, int $produtoId): void {
        // Verificar existência do produto e estoque
        $produto = $this->produtoDao->getProdutoById($produtoId);
        if (!$produto) {
            throw new Exception("Produto não encontrado.");
        }
        $estoque = $this->estoqueDao->getEstoqueByProdutoId($produtoId);
        if (!$estoque) {
            throw new Exception("Estoque não encontrado para o produto.");
        }
        $produto->setEstoque($estoque);

        // Geração de Pedido
        $pedido = $this->pedidoDao->getPedidoNaoConfirmado($clienteId);
        if (!$pedido) {
            // Gera um novo pedido caso não exista um pedido em aberto
            $numeroPedido = 'PED-' . date('YmdHis') . '-' . $clienteId;
            
            $pedido = new Pedido($numeroPedido, $clienteId);

            $pedidoId = $this->pedidoDao->create($pedido);
            $pedido->setId($pedidoId);
        }
        
        // Verificar se já existe um item para esse produto no pedido
        $itemExistente = $this->itemPedidoDao->getItemByPedidoEProduto($pedido->getId(), $produtoId);
        
        if ($itemExistente) {
            // Item já existe, apenas aumenta a quantidade
            $itemExistente->aumentarQuantidade(1);
            $this->itemPedidoDao->update($itemExistente);
            
        } else {
            // Item não existe, cria um novo
            $itemPedido = new ItemPedido($pedido->getId(), $produtoId, 1, $produto->getEstoque()->getPreco());
            $itemPedidoId = $this->itemPedidoDao->create($itemPedido);
        }
    }
}