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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->listarProdutosNoCarrinho();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'adicionar';
    
    switch ($action) {
        case 'adicionar':
            $controller->adicionarProdutoAoCarrinho($_POST['produto_id']);
            break;
        case 'remover':
            $controller->removerItem();
            break;
        case 'alterar_quantidade':
            $controller->alterarQuantidade();
            break;
        default:
            $controller->adicionarProdutoAoCarrinho($_POST['produto_id']);
    }
}

