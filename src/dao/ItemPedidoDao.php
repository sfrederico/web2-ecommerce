<?php

require_once __DIR__ . '/../model/ItemPedido.php';

class ItemPedidoDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function create(ItemPedido $itemPedido): int {
        $query = "INSERT INTO ITEM_PEDIDO (PEDIDO_ID, PRODUTO_ID, QUANTIDADE, PRECO_UNITARIO, SUBTOTAL) 
                  VALUES (:pedidoId, :produtoId, :quantidade, :precoUnitario, :subtotal) RETURNING ID";
        
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':pedidoId' => $itemPedido->getPedidoId(),
            ':produtoId' => $itemPedido->getProdutoId(),
            ':quantidade' => $itemPedido->getQuantidade(),
            ':precoUnitario' => $itemPedido->getPrecoUnitario(),
            ':subtotal' => $itemPedido->getSubtotal()
        ]);

        $id = (int) $stmt->fetchColumn();
        $itemPedido->setId($id);
        
        return $id;
    }
    
    public function update(ItemPedido $itemPedido): bool {
        $query = "UPDATE ITEM_PEDIDO SET 
                  QUANTIDADE = :quantidade, 
                  PRECO_UNITARIO = :precoUnitario, 
                  SUBTOTAL = :subtotal 
                  WHERE ID = :id";
        
        $stmt = $this->connection->prepare($query);
        
        return $stmt->execute([
            ':quantidade' => $itemPedido->getQuantidade(),
            ':precoUnitario' => $itemPedido->getPrecoUnitario(),
            ':subtotal' => $itemPedido->getSubtotal(),
            ':id' => $itemPedido->getId()
        ]);
    }

    public function getItemByPedidoEProduto(int $pedidoId, int $produtoId): ?ItemPedido {
        $query = "SELECT *
                  FROM ITEM_PEDIDO 
                  WHERE PEDIDO_ID = :pedidoId AND PRODUTO_ID = :produtoId";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':pedidoId' => $pedidoId,
            ':produtoId' => $produtoId
        ]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && !empty($row)) {
            $itemPedido = new ItemPedido(
                (int)$row['pedido_id'], 
                (int)$row['produto_id'], 
                (int)$row['quantidade'], 
                (float)$row['preco_unitario']
            );
            $itemPedido->setId((int)$row['id']);
            return $itemPedido;
        }
        
        return null;
    }

    public function getItensByPedidoId(int $pedidoId): array {
        $query = "SELECT ip.id, ip.pedido_id, ip.produto_id, ip.quantidade, ip.preco_unitario, ip.subtotal,
                         p.nome as produto_nome, p.descricao as produto_descricao, p.foto as produto_foto
                  FROM ITEM_PEDIDO ip
                  INNER JOIN produto p ON ip.produto_id = p.id
                  WHERE ip.pedido_id = :pedidoId";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':pedidoId' => $pedidoId]);
        
        $itens = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemPedido = new ItemPedido(
                (int)$row['pedido_id'],
                (int)$row['produto_id'],
                (int)$row['quantidade'],
                (float)$row['preco_unitario']
            );
            $itemPedido->setId((int)$row['id']);
            
            // Criar produto com dados básicos
            $produto = new Produto(
                $row['produto_nome'],
                $row['produto_descricao']
            );
            $produto->setId((int)$row['produto_id']);
            if ($row['produto_foto']) {
                $produto->setFoto($row['produto_foto']);
            }
            
            $itemPedido->setProduto($produto);
            $itens[] = $itemPedido;
        }
        
        return $itens;
    }

    public function delete(int $itemId): bool {
        $query = "DELETE FROM ITEM_PEDIDO WHERE ID = :id";
        $stmt = $this->connection->prepare($query);
        
        return $stmt->execute([':id' => $itemId]);
    }

    public function deleteByPedidoEProduto(int $pedidoId, int $produtoId): bool {
        $query = "DELETE FROM ITEM_PEDIDO WHERE PEDIDO_ID = :pedidoId AND PRODUTO_ID = :produtoId";
        $stmt = $this->connection->prepare($query);
        
        return $stmt->execute([
            ':pedidoId' => $pedidoId,
            ':produtoId' => $produtoId
        ]);
    }

}
