<?php

require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../dao/FornecedorDao.php';
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../dao/EstoqueDao.php';

class EstoqueService {
    private ProdutoDao $produtoDao;
    private FornecedorDao $fornecedorDao;
    private UsuarioDao $usuarioDao;
    private EstoqueDao $estoqueDao;

    public function __construct($dbConnection) {
        $this->produtoDao = new ProdutoDao($dbConnection);
        $this->usuarioDao = new UsuarioDao($dbConnection);
        $this->fornecedorDao = new FornecedorDao($dbConnection, $this->usuarioDao);
        $this->estoqueDao = new EstoqueDao($dbConnection);
    }

    public function buscarProdutosPorFornecedor(int $fornecedorId): array {
        $produtos = $this->produtoDao->getProdutosPorFornecedor($fornecedorId);

        foreach ($produtos as $produto) {
            $estoque = $this->estoqueDao->getEstoqueByProdutoId($produto->getId());
            if ($estoque) {
                $produto->setEstoque($estoque);
            }
        }

        return $produtos;
    }

    public function buscarProdutosPorFiltro(int $fornecedorId, string $search): array {
        $produtos = $this->produtoDao->getProdutosPorFiltro($fornecedorId, $search);

        foreach ($produtos as $produto) {
            $estoque = $this->estoqueDao->getEstoqueByProdutoId($produto->getId());
            if ($estoque) {
                $produto->setEstoque($estoque);
            }
        }

        return $produtos;
    }

}