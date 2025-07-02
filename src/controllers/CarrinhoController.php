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
}