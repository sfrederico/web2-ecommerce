<?php

require_once __DIR__ . '/../services/EstoqueService.php';

class EstoqueController {
    private EstoqueService $estoqueService;

    public function __construct($dbConnection) {
        $this->estoqueService = new EstoqueService($dbConnection);
    }

    public function listarProdutos(int $fornecedorId) {
        $search = $_GET['search'] ?? null;

        if (empty($search)) {
            $produtos = $this->estoqueService->buscarProdutosPorFornecedor($fornecedorId);
        } else {
            $produtos = $this->estoqueService->buscarProdutosPorFiltro($fornecedorId, $search);
        }

        include __DIR__ . '/../views/estoque/lista.php';
    }
}