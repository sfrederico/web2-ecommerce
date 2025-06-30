<?php

include_once(__DIR__ . '/../model/Produto.php');

class ProdutoDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function create(Produto $produto): int {
        $query = "INSERT INTO produto (nome, descricao, fornecedor_id) VALUES (:nome, :descricao, :fornecedorId) RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':fornecedorId' => $produto->getFornecedor()->getUsuario()->getId(),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function update(Produto $produto): bool {
        $query = "UPDATE produto SET nome = :nome, descricao = :descricao, foto = :foto WHERE id = :id";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':foto' => $produto->getFoto(),
            ':id' => $produto->getId(),
        ]);
    }

    public function updateFoto(int $produtoId, string $fotoPath): bool {
        $query = "UPDATE produto SET foto = :foto WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([
            ':foto' => $fotoPath,
            ':id' => $produtoId,
        ]);
    }

    public function getProdutoById(int $id): ?Produto {
        $query = "SELECT * FROM produto WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $produto = new Produto($row['nome'], $row['descricao']);
            $produto->setId($row['id']);
            $produto->setFoto($row['foto'] ?? null);
            return $produto;
        }

        return null;
    }

    public function getTodosProdutos(): array {
        $query = "SELECT * FROM produto";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produto = new Produto($row['nome'], $row['descricao']);
            $produto->setId($row['id']);
            $produto->setFoto($row['foto'] ?? null);
            $produto->setFornecedorId($row['fornecedor_id']);
            $produtos[] = $produto;
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
            $produto->setFoto($row['foto']);
            $produtos[] = $produto;
        }

        return $produtos;
    }

    public function getProdutosPorFiltro(int $fornecedorId, string $search): array {
        $query = "SELECT * FROM produto WHERE fornecedor_id = :fornecedorId AND (CAST(id AS TEXT) = :search OR nome ILIKE :searchLike)";
        $params = [
            ':fornecedorId' => $fornecedorId,
            ':search' => $search,
            ':searchLike' => '%' . $search . '%'
        ];

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produto = new Produto($row['nome'], $row['descricao']);
            $produto->setId($row['id']);
            $produto->setFoto($row['foto']);
            $produtos[] = $produto;
        }

        return $produtos;
    }
}
?>