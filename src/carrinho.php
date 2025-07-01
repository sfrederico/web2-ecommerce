<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/controllers/CarrinhoController.php';


if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['papel'] !== 'cliente') {
    header("Location: /login.php");
    exit();
}

$controller = new CarrinhoController($dbConnection);
$controller->listarProdutosNoCarrinho();

