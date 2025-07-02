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

}
