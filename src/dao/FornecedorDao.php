<?php
require_once __DIR__ . '/../model/Fornecedor.php';

class FornecedorDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function create(Fornecedor $fornecedor): int {
        $query = "INSERT INTO fornecedor (usuario_id, descricao) VALUES (:USUARIO_ID, :DESCRICAO) RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':USUARIO_ID' => $fornecedor->getUsuario()->getId(),
            ':DESCRICAO' => $fornecedor->getDescricao(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function update(Fornecedor $fornecedor): int {
        $query = "UPDATE fornecedor SET descricao = :DESCRICAO WHERE usuario_id = :USUARIO_ID RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':USUARIO_ID' => $fornecedor->getId(),
            ':DESCRICAO' => $fornecedor->getDescricao(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function delete(int $usuarioId): bool {
        $query = "DELETE FROM fornecedor WHERE usuario_id = :USUARIO_ID";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([':USUARIO_ID' => $usuarioId]);
    }
}