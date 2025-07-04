<?php

require_once __DIR__ . '/../dao/PedidoDao.php';

class PedidoService {
    private PedidoDao $pedidoDao;

    public function __construct(private $dbConnection) {
        $this->pedidoDao = new PedidoDao($dbConnection);
    }

    public function getPedidosConfirmadosDoCliente(int $clienteId): array {
        return $this->pedidoDao->getPedidosByClienteId($clienteId);
    }
}
