<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/controllers/PerfilController.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit();
}

$perfilController = new PerfilController($dbConnection);
$usuarioId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && $_GET['action'] === 'atualizar') {
        $perfilController->atualizaPerfil($usuarioId);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'deletar') {
        $perfilController->deletaUsuario($usuarioId);
    }
} else {
    $perfilController->mostraUsuario($usuarioId);
}

