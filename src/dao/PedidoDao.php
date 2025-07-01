<?php
require_once __DIR__ . '/../model/Pedido.php';

class PedidoDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function create(Pedido $pedido): int {
        $query = "INSERT INTO pedido (numero, cliente_id, situacao, confirmado, valor_total) 
                  VALUES (:NUMERO, :CLIENTE_ID, :SITUACAO, :CONFIRMADO, :VALOR_TOTAL) 
                  RETURNING id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':NUMERO', $pedido->getNumero());
        $stmt->bindParam(':CLIENTE_ID', $pedido->getClienteId());
        $stmt->bindParam(':SITUACAO', $pedido->getSituacao());
        $stmt->bindParam(':CONFIRMADO', $pedido->isConfirmado(), PDO::PARAM_BOOL);
        $stmt->bindParam(':VALOR_TOTAL', $pedido->getValorTotal());

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getPedidoNaoConfirmado(int $clienteId): ?Pedido {
        $query = "SELECT * FROM pedido WHERE cliente_id = :CLIENTE_ID AND confirmado = false LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':CLIENTE_ID' => $clienteId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $pedido = new Pedido($row['numero'], $row['cliente_id']);
            $pedido->setId($row['id']);
            $pedido->setDataPedido($row['data_pedido']);
            $pedido->setSituacao($row['situacao']);
            $pedido->setConfirmado($row['confirmado']);
            $pedido->setValorTotal($row['valor_total']);
            return $pedido;
        }

        return null;
    }
}
