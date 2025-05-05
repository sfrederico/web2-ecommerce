<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/controllers/ProdutoController.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['papel'] !== 'fornecedor') {
    header("Location: /login.php");
    exit();
}

$controller = new ProdutoController($dbConnection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    switch ($acao) {
        case 'atualizar':
            $controller->atualizarProduto((int)$_GET['id'], $_POST);
            break;
        case 'criar':
            $controller->salvarProduto($_POST);
            break;
        case 'excluir':
            $controller->excluirProduto((int)$_POST['id']);
            break;
        default:
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $acao = $_GET['acao'] ?? '';
    if ($acao === 'editar') {
        $controller->editarProduto((int)$_GET['id']);
    } else {
        $controller->mostrarFormularioCriacao();
    }
}

