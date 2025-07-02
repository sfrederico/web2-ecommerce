<?php

require_once __DIR__ . '/../services/CarrinhoService.php';

class CarrinhoController {
    private CarrinhoService $carrinhoService;

    public function __construct($dbConnection) {
        $this->carrinhoService = new CarrinhoService($dbConnection);
    }

    public function listarProdutosNoCarrinho() {
        $clienteId = $_SESSION['user']['id'];
        $itens = $this->carrinhoService->listarItensDoCarrinho($clienteId);
        $total = $this->carrinhoService->calcularTotalCarrinho($clienteId);
        
        include __DIR__ . '/../views/carrinho/index.php';
    }

    public function adicionarProdutoAoCarrinho($productId) {
        $clienteId = $_SESSION['user']['id'];
        $this->carrinhoService->adicionarProduto($clienteId, $productId);
        header("Location: /carrinho.php");
    }

    public function removerItem() {
        if (!isset($_POST['item_id'])) {
            $_SESSION['erro'] = "Item não especificado.";
            header("Location: /carrinho.php");
            return;
        }
        
        try {
            $clienteId = $_SESSION['user']['id'];
            $itemId = (int)$_POST['item_id'];
            
            $this->carrinhoService->removerItem($clienteId, $itemId);
            $_SESSION['sucesso'] = "Item removido do carrinho!";
            
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
        }
        
        header("Location: /carrinho.php");
    }

    public function alterarQuantidade() {
        if (!isset($_POST['item_id']) || !isset($_POST['quantidade'])) {
            $_SESSION['erro'] = "Dados incompletos.";
            header("Location: /carrinho.php");
            return;
        }
        
        try {
            $clienteId = $_SESSION['user']['id'];
            $itemId = (int)$_POST['item_id'];
            $quantidade = (int)$_POST['quantidade'];
            
            $this->carrinhoService->alterarQuantidade($clienteId, $itemId, $quantidade);
            $_SESSION['sucesso'] = "Quantidade atualizada!";
            
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
        }
        
        header("Location: /carrinho.php");
    }
}