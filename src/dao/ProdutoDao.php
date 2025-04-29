<?php

include_once(__DIR__ . '/../model/Produto.php');

class ProdutoDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function salvarProduto(Produto $produto): bool {
        $query = "INSERT INTO produto (nome, descricao) VALUES (:NOME, :DESCRICAO)";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':NOME' => $produto->getNome(),
            ':DESCRICAO' => $produto->getDescricao(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function atualizarProduto(int $id, Produto $produto): bool {
        $query = "UPDATE produto SET nome = :NOME, descricao = :DESCRICAO WHERE id = :ID";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            ':NOME' => $produto->getNome(),
            ':DESCRICAO' => $produto->getDescricao(),
            ':ID' => $id,
        ]);
    }

    public function getProdutoById(int $id): ?Produto {
        $query = "SELECT * FROM produto WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Produto(
                $row['nome'],
                $row['descricao']
            );
        }
        return null;
    }

    public function getTodosProdutos(): array {
        $query = "SELECT * FROM produto";
        $stmt = $this->connection->query($query);

        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = new Produto(
                $row['nome'],
                $row['descricao']
            );
        }
        return $produtos;
    }

    public function deletarProduto(int $id): bool {
        $query = "DELETE FROM produto WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
?>