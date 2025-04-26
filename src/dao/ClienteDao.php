<?php
require_once __DIR__ . '/../model/Cliente.php';

class ClienteDao {
    private $connection;
    private $usuarioDao;

    public function __construct($dbConnection, ?UsuarioDao $usuarioDao = null) {
        $this->connection = $dbConnection;
        $this->usuarioDao = $usuarioDao;
    }

    public function create(Cliente $cliente): int {
        $query = "INSERT INTO cliente (id, cartao_credito) VALUES (:ID, :CARTAO_CREDITO) RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':ID' => $cliente->getUsuario()->getId(),
            ':CARTAO_CREDITO' => $cliente->getCartaoCredito(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function update(Cliente $cliente): int {
        $query = "UPDATE cliente SET cartao_credito = :CARTAO_CREDITO WHERE id = :ID RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':ID' => $cliente->getUsuario()->getId(),
            ':CARTAO_CREDITO' => $cliente->getCartaoCredito(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function delete(int $usuarioId): bool {
        $query = "DELETE FROM cliente WHERE usuario_id = :USUARIO_ID";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([':USUARIO_ID' => $usuarioId]);
    }

    public function getClienteById(int $id): ?Cliente {
        $query = "SELECT * FROM cliente WHERE id = :ID";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':ID', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $usuario = $this->usuarioDao->getUsuarioById($row['id']);
            return new Cliente(
                $usuario,
                $row['cartao_credito']
            );
        }
        return null;
    }
}