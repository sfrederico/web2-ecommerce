<?php
require_once __DIR__ . '/../model/Cliente.php';

class ClienteDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function create(Cliente $cliente): int {
        $query = "INSERT INTO cliente (usuario_id, cartao_credito) VALUES (:USUARIO_ID, :CARTAO_CREDITO) RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':USUARIO_ID' => $cliente->getUsuario()->getId(),
            ':CARTAO_CREDITO' => $cliente->getCartaoCredito(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function update(Cliente $cliente): int {
        $query = "UPDATE cliente SET cartao_credito = :CARTAO_CREDITO WHERE usuario_id = :USUARIO_ID RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':USUARIO_ID' => $cliente->getId(),
            ':CARTAO_CREDITO' => $cliente->getCartaoCredito(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function delete(int $usuarioId): bool {
        $query = "DELETE FROM cliente WHERE usuario_id = :USUARIO_ID";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([':USUARIO_ID' => $usuarioId]);
    }
}