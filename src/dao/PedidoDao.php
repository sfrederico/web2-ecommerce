<?php
require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/Cliente.php';
require_once __DIR__ . '/../model/Usuario.php';

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
        
        // Armazenar valores em variáveis para bindParam
        $numero = $pedido->getNumero();
        $clienteId = $pedido->getClienteId();
        $situacao = $pedido->getSituacao();
        $confirmado = $pedido->isConfirmado();
        $valorTotal = $pedido->getValorTotal();
        
        $stmt->bindParam(':NUMERO', $numero);
        $stmt->bindParam(':CLIENTE_ID', $clienteId);
        $stmt->bindParam(':SITUACAO', $situacao);
        $stmt->bindParam(':CONFIRMADO', $confirmado, PDO::PARAM_BOOL);
        $stmt->bindParam(':VALOR_TOTAL', $valorTotal);

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function update(Pedido $pedido): bool {
        $query = "UPDATE PEDIDO SET 
                    numero = :numero,
                    cliente_id = :cliente_id,
                    situacao = :situacao,
                    confirmado = :confirmado,
                    valor_total = :valor_total,
                    data_entrega = :data_entrega
                  WHERE id = :id";
        
        $stmt = $this->connection->prepare($query);

        // Armazenar valores em variáveis para bindParam
        $numero = $pedido->getNumero();
        $clienteId = $pedido->getClienteId();
        $situacao = $pedido->getSituacao();
        $confirmado = $pedido->isConfirmado();
        $valorTotal = $pedido->getValorTotal();
        $dataEntrega = $pedido->getDataEntrega();
        $id = $pedido->getId();

        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':cliente_id', $clienteId);
        $stmt->bindParam(':situacao', $situacao);
        $stmt->bindParam(':confirmado', $confirmado, PDO::PARAM_BOOL);
        $stmt->bindParam(':valor_total', $valorTotal);
        $stmt->bindParam(':data_entrega', $dataEntrega);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function getPedidosByClienteId(int $clienteId): array {
        $query = "SELECT * FROM PEDIDO 
                  WHERE CLIENTE_ID = :clienteId
                  AND CONFIRMADO = TRUE
                  ORDER BY DATA_PEDIDO DESC";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':clienteId' => $clienteId]);
        
        $pedidos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pedido = new Pedido($row['numero'], $row['cliente_id']);
            $pedido->setId($row['id']);
            $pedido->setDataPedido($row['data_pedido']);
            $pedido->setDataEntrega($row['data_entrega']);
            $pedido->setSituacao($row['situacao']);
            $pedido->setConfirmado($row['confirmado']);
            $pedido->setValorTotal($row['valor_total']);
            
            $pedidos[] = $pedido;
        }
        
        return $pedidos;
    }

    public function getPedidoNaoConfirmado(int $clienteId): ?Pedido {
        $query = "SELECT * FROM PEDIDO 
                  WHERE CLIENTE_ID = :clienteId AND CONFIRMADO = FALSE 
                  LIMIT 1";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':clienteId' => $clienteId]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $pedido = new Pedido($row['numero'], $row['cliente_id']);
            $pedido->setId($row['id']);
            $pedido->setDataPedido($row['data_pedido']);
            $pedido->setDataEntrega($row['data_entrega']);
            $pedido->setSituacao($row['situacao']);
            $pedido->setConfirmado($row['confirmado']);
            $pedido->setValorTotal($row['valor_total']);
            return $pedido;
        }

        return null;
    }

    public function getPedidoById(int $pedidoId): ?Pedido {
        $query = "SELECT * FROM PEDIDO WHERE ID = :pedidoId";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':pedidoId' => $pedidoId]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $pedido = new Pedido($row['numero'], $row['cliente_id']);
            $pedido->setId($row['id']);
            $pedido->setDataPedido($row['data_pedido']);
            $pedido->setDataEntrega($row['data_entrega']);
            $pedido->setSituacao($row['situacao']);
            $pedido->setConfirmado($row['confirmado']);
            $pedido->setValorTotal($row['valor_total']);
            return $pedido;
        }

        return null;
    }

    public function confirmarPedido(int $pedidoId): bool {
        $query = "UPDATE PEDIDO SET confirmado = TRUE WHERE id = :pedidoId";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function getPedidosPorFornecedor(int $fornecedorId): array {
        $query = "SELECT DISTINCT p.*, 
                         c.id as cliente_id, c.cartao_credito,
                         u.id as usuario_id, u.nome as cliente_nome, u.email as cliente_email, 
                         u.telefone as cliente_telefone, u.nome_usuario as cliente_nome_usuario
                  FROM pedido p
                  INNER JOIN item_pedido ip ON p.id = ip.pedido_id
                  INNER JOIN produto prod ON ip.produto_id = prod.id
                  INNER JOIN cliente c ON p.cliente_id = c.id
                  INNER JOIN usuario u ON c.id = u.id
                  WHERE prod.fornecedor_id = :fornecedorId
                  AND p.confirmado = TRUE
                  ORDER BY p.data_pedido DESC";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':fornecedorId' => $fornecedorId]);
        
        $pedidos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pedido = new Pedido($row['numero'], $row['cliente_id']);
            $pedido->setId($row['id']);
            $pedido->setDataPedido($row['data_pedido']);
            $pedido->setDataEntrega($row['data_entrega']);
            $pedido->setSituacao($row['situacao']);
            $pedido->setConfirmado($row['confirmado']);
            $pedido->setValorTotal($row['valor_total']);
            
            // Criar e popular o objeto Cliente/Usuario
            $usuario = new Usuario(
                $row['usuario_id'],
                $row['cliente_nome_usuario'],
                '', // senha não necessária aqui
                $row['cliente_nome'],
                'cliente', // papel padrão
                $row['cliente_telefone'],
                $row['cliente_email']
            );
            
            $cliente = new Cliente($usuario, $row['cartao_credito'] ?? '');
            
            $pedido->setCliente($cliente);
            
            $pedidos[] = $pedido;
        }
        
        return $pedidos;
    }

    public function verificarPedidoPertenceAoFornecedor(int $pedidoId, int $fornecedorId): bool {
        $query = "SELECT COUNT(*) FROM pedido p 
                  INNER JOIN item_pedido ip ON p.id = ip.pedido_id
                  INNER JOIN produto prod ON ip.produto_id = prod.id
                  WHERE p.id = :pedidoId AND prod.fornecedor_id = :fornecedorId";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':pedidoId' => $pedidoId,
            ':fornecedorId' => $fornecedorId
        ]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    public function atualizarStatusEDataEntrega(int $pedidoId, string $novoStatus, ?string $novaDataEntrega): bool {
        $query = "UPDATE pedido SET 
                    situacao = :situacao,
                    data_entrega = :data_entrega
                  WHERE id = :id";
        
        $stmt = $this->connection->prepare($query);
        
        return $stmt->execute([
            ':situacao' => $novoStatus,
            ':data_entrega' => $novaDataEntrega ?: null,
            ':id' => $pedidoId
        ]);
    }

    public function getPedidosPorFornecedorETermo(int $fornecedorId, string $termoBusca): array {
        $query = "SELECT DISTINCT p.*, 
                         c.id as cliente_id, c.cartao_credito,
                         u.id as usuario_id, u.nome as cliente_nome, u.email as cliente_email, 
                         u.telefone as cliente_telefone, u.nome_usuario as cliente_nome_usuario
                  FROM pedido p
                  INNER JOIN item_pedido ip ON p.id = ip.pedido_id
                  INNER JOIN produto prod ON ip.produto_id = prod.id
                  INNER JOIN cliente c ON p.cliente_id = c.id
                  INNER JOIN usuario u ON c.id = u.id
                  WHERE prod.fornecedor_id = :fornecedorId
                  AND p.confirmado = TRUE
                  AND (p.numero ILIKE :termoBusca OR u.nome ILIKE :termoBusca)
                  ORDER BY p.data_pedido DESC";
        
        $stmt = $this->connection->prepare($query);
        $termoComWildcard = '%' . $termoBusca . '%';
        $stmt->execute([
            ':fornecedorId' => $fornecedorId,
            ':termoBusca' => $termoComWildcard
        ]);
        
        $pedidos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pedido = new Pedido($row['numero'], $row['cliente_id']);
            $pedido->setId($row['id']);
            $pedido->setDataPedido($row['data_pedido']);
            $pedido->setDataEntrega($row['data_entrega']);
            $pedido->setSituacao($row['situacao']);
            $pedido->setConfirmado($row['confirmado']);
            $pedido->setValorTotal($row['valor_total']);
            
            // Criar e popular o objeto Cliente/Usuario
            $usuario = new Usuario(
                $row['usuario_id'],
                $row['cliente_nome_usuario'],
                '', // senha não necessária aqui
                $row['cliente_nome'],
                'cliente', // papel padrão
                $row['cliente_telefone'],
                $row['cliente_email']
            );
            
            $cliente = new Cliente($usuario, $row['cartao_credito'] ?? '');
            
            $pedido->setCliente($cliente);
            
            $pedidos[] = $pedido;
        }
        
        return $pedidos;
    }
}
