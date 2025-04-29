<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/controllers/ProdutoController.php';


if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit();
}

$controller = new ProdutoController($dbConnection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ProdutoController = new ProdutoController($dbConnection);
    $ProdutoController->criaProduto();
} else {
    include_once(__DIR__ . '/views/produto/produto_form.php');
}

