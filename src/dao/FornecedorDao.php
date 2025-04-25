<?php
require_once __DIR__ . '/../model/Fornecedor.php';

class FornecedorDao {
    private $connection;
    private $usuarioDao;

    public function __construct($dbConnection, ?UsuarioDao $usuarioDao = null) {
        $this->connection = $dbConnection;
        $this->usuarioDao = $usuarioDao;
    }

    public function create(Fornecedor $fornecedor): int {
        $query = "INSERT INTO fornecedor (id, descricao) VALUES (:ID, :DESCRICAO) RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':ID' => $fornecedor->getUsuario()->getId(),
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

    public function getFornecedorById(int $id): ?Fornecedor {
        $query = "SELECT * FROM fornecedor WHERE id = :ID";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':ID', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $usuario = $this->usuarioDao->getUsuarioById($row['id']);
            return new Fornecedor(
                $usuario,
                $row['descricao']
            );
        }
        return null;
    }
}