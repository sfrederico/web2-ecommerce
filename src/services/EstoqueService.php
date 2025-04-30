<?php

require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../dao/FornecedorDao.php';
require_once __DIR__ . '/../dao/UsuarioDao.php';

class EstoqueService {
    private ProdutoDao $produtoDao;
    private FornecedorDao $fornecedorDao;
    private UsuarioDao $usuarioDao;

    public function __construct($dbConnection) {
        $this->produtoDao = new ProdutoDao($dbConnection);
        $this->usuarioDao = new UsuarioDao($dbConnection);
        $this->fornecedorDao = new FornecedorDao($dbConnection, $this->usuarioDao);
    }

    public function buscarProdutosPorFornecedor(int $fornecedorId): array {
        return $this->produtoDao->getProdutosPorFornecedor($fornecedorId);
    }

}