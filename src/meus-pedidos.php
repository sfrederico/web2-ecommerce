<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/controllers/PedidoController.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

// Verificar se usuário está logado e é cliente
if (!isset($_SESSION['user']) || $_SESSION['user']['papel'] !== 'cliente') {
    header("Location: /login.php");
    exit();
}

$controller = new PedidoController($dbConnection);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Requisição para detalhes
    if (isset($_GET['action']) && $_GET['action'] === 'detalhes' && isset($_GET['pedido_id'])) {
        $controller->getDetalhesPedido((int)$_GET['pedido_id']);
        exit;
    }
    
    $controller->listarMeusPedidos();
}
