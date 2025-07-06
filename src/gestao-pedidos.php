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
    $controller->mostrarPainelGestao();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ações POST futuras para processar pedidos
}
