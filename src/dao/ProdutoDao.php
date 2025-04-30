<?php

include_once(__DIR__ . '/../model/Produto.php');

class ProdutoDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function create(Produto $produto): bool {
        $query = "INSERT INTO produto (nome, descricao, fornecedor_id) VALUES (:nome, :descricao, :fornecedorId)";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':fornecedorId' => $produto->getFornecedor()->getUsuario()->getId(),
        ]);
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

    public function getProdutosPorFornecedor(int $fornecedorId): array {
        $query = "SELECT * FROM produto WHERE fornecedor_id = :fornecedorId";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':fornecedorId' => $fornecedorId]);

        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produto = new Produto($row['nome'], $row['descricao']);
            $produto->setId($row['id']);
            $produtos[] = $produto;
        }

        return $produtos;
    }
}
?>