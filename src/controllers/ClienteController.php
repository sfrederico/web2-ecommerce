<?php
require_once __DIR__ . '/../dao/ClienteDao.php';
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../model/Cliente.php';

class ClienteController {
    private ClienteDao $clienteDao;
    private UsuarioDao $usuarioDao;

    public function __construct($dbConnection) {
        $this->clienteDao = new ClienteDao($dbConnection);
        $this->usuarioDao = new UsuarioDao($dbConnection);
    }

    public function createCliente(string $usuarioId, string $cartaoCredito): int {
        $usuario = $this->usuarioDao->getUsuarioById($usuarioId);
        $cliente = new Cliente($usuario, $cartaoCredito);
        return $this->clienteDao->create($cliente);
    }
}