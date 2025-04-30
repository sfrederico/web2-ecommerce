<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/controllers/EstoqueController.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['papel'] !== 'fornecedor') {
    header("Location: /login.php");
    exit();
}

$controller = new EstoqueController($dbConnection);
$controller->listarProdutos($_SESSION['user']['id']);

