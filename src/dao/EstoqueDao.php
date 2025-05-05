<?php
require_once __DIR__ . '/../model/Estoque.php';

class EstoqueDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function create(Estoque $estoque): int {
        $query = "INSERT INTO estoque (produto_id, quantidade, preco) VALUES (:PRODUTO_ID, :QUANTIDADE, :PRECO) RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':PRODUTO_ID' => $estoque->getProduto()->getId(),
            ':QUANTIDADE' => $estoque->getQuantidade(),
            ':PRECO' => $estoque->getPreco(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function update(Estoque $estoque): int {
        $query = "UPDATE estoque SET quantidade = :QUANTIDADE, preco = :PRECO WHERE id = :ID RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':ID' => $estoque->getId(),
            ':QUANTIDADE' => $estoque->getQuantidade(),
            ':PRECO' => $estoque->getPreco(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function delete(int $id): bool {
        $query = "DELETE FROM estoque WHERE id = :ID";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([':ID' => $id]);
    }
}