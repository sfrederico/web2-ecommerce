<?php

require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../dao/ClienteDao.php';
require_once __DIR__ . '/../dao/FornecedorDao.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../model/Cliente.php';
require_once __DIR__ . '/../model/Fornecedor.php';

class ContaService {
    private UsuarioDao $usuarioDao;
    private ClienteDao $clienteDao;
    private FornecedorDao $fornecedorDao;

    public function __construct($dbConnection) {
        $this->usuarioDao = new UsuarioDao($dbConnection);
        $this->clienteDao = new ClienteDao($dbConnection, $this->usuarioDao);
        $this->fornecedorDao = new FornecedorDao($dbConnection, $this->usuarioDao);
    }

    public function criarConta(array $dados): void {
        $usuario = new Usuario(
            0,
            $dados['nomeUsuario'],
            $dados['senha'],
            $dados['nome'],
            $dados['papel'],
            $dados['telefone'],
            $dados['email']
        );
        $usuarioId = $this->usuarioDao->create($usuario);
        $usuario->setId($usuarioId);

        if ($dados['papel'] === 'cliente') {
            $cliente = new Cliente($usuario, $dados['cartaoCredito']);
            $this->clienteDao->create($cliente);
        } elseif ($dados['papel'] === 'fornecedor') {
            $fornecedor = new Fornecedor($usuario, $dados['descricao']);
            $this->fornecedorDao->create($fornecedor);
        } else {
            throw new Exception("Tipo de conta inválido.");
        }
    }
}