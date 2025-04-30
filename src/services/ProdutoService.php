<?php

require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../dao/FornecedorDao.php';

class ProdutoService {
    private ProdutoDao $produtoDao;
    private UsuarioDao $usuarioDao;
    private FornecedorDao $fornecedorDao;

    public function __construct($dbConnection) {
        $this->produtoDao = new ProdutoDao($dbConnection);
        $this->usuarioDao = new UsuarioDao($dbConnection);
        $this->fornecedorDao = new FornecedorDao($dbConnection, $this->usuarioDao);
    }

    public function criarProduto(string $nome, string $descricao, int $fornecedorId): void {
        $fornecedor = $this->fornecedorDao->getFornecedorById($fornecedorId);

        if (!$fornecedor) {
            throw new Exception("Fornecedor não encontrado.");
        }

        $produto = new Produto($nome, $descricao);
        $produto->setFornecedor($fornecedor);

        $this->produtoDao->create($produto);
    }
}