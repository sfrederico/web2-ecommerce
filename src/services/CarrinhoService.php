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

    private function atualizarTotalPedido(int $pedidoId): void {
        $itens = $this->itemPedidoDao->getItensByPedidoId($pedidoId);
        $total = 0.0;
        
        foreach ($itens as $item) {
            $total += $item->getSubtotal();
        }
        
        // Buscar o pedido e atualizar o total
        $pedido = $this->pedidoDao->getPedidoById($pedidoId);
        if ($pedido) {
            $pedido->setValorTotal($total);
            $this->pedidoDao->update($pedido);
        }
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

        // Atualizar o total do pedido após adicionar item
        $this->atualizarTotalPedido($pedido->getId());
    }
    
    public function listarItensDoCarrinho(int $clienteId): array {
        // Buscar pedido não confirmado (carrinho atual)
        $pedido = $this->pedidoDao->getPedidoNaoConfirmado($clienteId);
        
        if (!$pedido) {
            return []; // Carrinho vazio
        }
        
        // Buscar itens do pedido
        $itens = $this->itemPedidoDao->getItensByPedidoId($pedido->getId());
        
        return $itens;
    }

    public function calcularTotalCarrinho(int $clienteId): float {
        $itens = $this->listarItensDoCarrinho($clienteId);
        $total = 0.0;
        
        foreach ($itens as $item) {
            $total += $item->getSubtotal();
        }
        
        return $total;
    }

    public function removerItem(int $clienteId, int $itemId): bool {
        // Buscar pedido não confirmado do cliente
        $pedido = $this->pedidoDao->getPedidoNaoConfirmado($clienteId);
        
        if (!$pedido) {
            throw new Exception("Carrinho não encontrado.");
        }
        
        // Verificar se o item pertence ao pedido do cliente (segurança)
        $itens = $this->itemPedidoDao->getItensByPedidoId($pedido->getId());
        $itemEncontrado = false;
        
        foreach ($itens as $item) {
            if ($item->getId() === $itemId) {
                $itemEncontrado = true;
                break;
            }
        }
        
        if (!$itemEncontrado) {
            throw new Exception("Item não encontrado no carrinho.");
        }
        
        // Remover o item
        $resultado = $this->itemPedidoDao->delete($itemId);

        if ($resultado) {
            // Atualizar o total do pedido após remover item
            $this->atualizarTotalPedido($pedido->getId());
        }

        return $resultado;
    }

    public function alterarQuantidade(int $clienteId, int $itemId, int $novaQuantidade): bool {
        if ($novaQuantidade <= 0) {
            return $this->removerItem($clienteId, $itemId);
        }
        
        // Buscar pedido não confirmado do cliente
        $pedido = $this->pedidoDao->getPedidoNaoConfirmado($clienteId);
        
        if (!$pedido) {
            throw new Exception("Carrinho não encontrado.");
        }
        
        // Buscar item para verificar se existe e pertence ao cliente
        $itens = $this->itemPedidoDao->getItensByPedidoId($pedido->getId());
        $itemEncontrado = null;
        
        foreach ($itens as $item) {
            if ($item->getId() === $itemId) {
                $itemEncontrado = $item;
                break;
            }
        }
        
        if (!$itemEncontrado) {
            throw new Exception("Item não encontrado no carrinho.");
        }
        
        // Atualizar quantidade
        $itemEncontrado->setQuantidade($novaQuantidade);
        
        $resultado = $this->itemPedidoDao->update($itemEncontrado);

        if ($resultado) {
            // Atualizar o total do pedido após alterar quantidade
            $this->atualizarTotalPedido($pedido->getId());
        }

        return $resultado;
    }
}