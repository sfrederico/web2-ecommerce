<?php
require_once __DIR__ . '/../model/Estoque.php';

class HomeDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function getEstoqueByProdutoId(int $produtoId): ?Estoque {
        $query = "SELECT * FROM estoque WHERE produto_id = :produtoId";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':produtoId' => $produtoId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $estoque = new Estoque($row['quantidade'], $row['preco']);
            $estoque->setId($row['id']);
            return $estoque;
        }

        return null;
    }
}