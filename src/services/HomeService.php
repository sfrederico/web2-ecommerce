<?php

require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../dao/FornecedorDao.php';
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../dao/EstoqueDao.php';

class HomeService {
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

    public function buscarTodosProdutos() {
        $produtos = $this->produtoDao->getTodosProdutos();

        foreach ($produtos as $produto) {
            $estoque = $this->estoqueDao->getEstoqueByProdutoId($produto->getId());
            if ($estoque) {
                $produto->setEstoque($estoque);
            }
            $fornecedor = $this->fornecedorDao->getFornecedorById($produto->getFornecedorId());
            if ($fornecedor) {
                $produto->setFornecedor($fornecedor);
            }
            
        }

        return $produtos;
    }

    public function buscarTodosProdutosPorFiltro($search) {
        $produtos = $this->produtoDao->getTodosProdutosPorFiltro($search);

        foreach ($produtos as $produto) {
            $estoque = $this->estoqueDao->getEstoqueByProdutoId($produto->getId());
            if ($estoque) {
                $produto->setEstoque($estoque);
            }
            $fornecedor = $this->fornecedorDao->getFornecedorById($produto->getFornecedorId());
            if ($fornecedor) {
                $produto->setFornecedor($fornecedor);
            }
        }

        return $produtos;
    }
    
}