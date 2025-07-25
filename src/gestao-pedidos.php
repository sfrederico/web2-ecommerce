<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/controllers/GestaoPedidosController.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['papel'] !== 'fornecedor') {
    header("Location: /login.php");
    exit();
}

$controller = new GestaoPedidosController($dbConnection);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Rota para detalhes do pedido
    if (isset($_GET['action']) && $_GET['action'] === 'detalhes' && isset($_GET['pedido_id'])) {
        $controller->getDetalhesPedido((int)$_GET['pedido_id']);
        exit;
    }
    
    $controller->mostrarPainelGestao();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar pedido
    if (isset($_POST['action']) && $_POST['action'] === 'atualizar') {
        header('Content-Type: application/json');
        $controller->atualizarPedido();
        exit;
    }
}
