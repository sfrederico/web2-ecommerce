<?php

require_once __DIR__ . '/../services/EstoqueService.php';

class EstoqueController {
    private EstoqueService $estoqueService;

    public function __construct($dbConnection) {
        $this->estoqueService = new EstoqueService($dbConnection);
    }

    public function listarProdutos(int $fornecedorId) {
        $produtos = $this->estoqueService->buscarProdutosPorFornecedor($fornecedorId);
        include __DIR__ . '/../views/estoque/lista.php';
    }
}