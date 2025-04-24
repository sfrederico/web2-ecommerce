<?php
require_once __DIR__ . '/../dao/FornecedorDao.php';
require_once __DIR__ . '/../model/Fornecedor.php';

class FornecedorController {
    private FornecedorDao $fornecedorDao;
    private UsuarioDao $usuarioDao;

    public function __construct($dbConnection) {
        $this->fornecedorDao = new FornecedorDao($dbConnection);
        $this->usuarioDao = new UsuarioDao($dbConnection);
    }

    public function createFornecedor(string $usuarioId, string $descricao): int {
        $usuario = $this->usuarioDao->getUsuarioById($usuarioId);
        $fornecedor = new Fornecedor($usuario, $descricao);
        return $this->fornecedorDao->create($fornecedor);
    }
}