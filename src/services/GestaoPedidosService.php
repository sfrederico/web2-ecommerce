<?php

require_once __DIR__ . '/../dao/PedidoDao.php';
require_once __DIR__ . '/../dao/ItemPedidoDao.php';
require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../model/Pedido.php';

class GestaoPedidosService {
    private PedidoDao $pedidoDao;
    private ItemPedidoDao $itemPedidoDao;
    private ProdutoDao $produtoDao;

    public function __construct($dbConnection) {
        $this->pedidoDao = new PedidoDao($dbConnection);
        $this->itemPedidoDao = new ItemPedidoDao($dbConnection);
        $this->produtoDao = new ProdutoDao($dbConnection);
    }

    public function buscarPedidosPorFornecedor(int $fornecedorId): array {
        return $this->pedidoDao->getPedidosPorFornecedor($fornecedorId);
    }
}
