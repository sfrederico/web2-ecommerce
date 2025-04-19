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

$controller = new PerfilController($dbConnection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->atualizaPerfil();
} else {
    $controller->mostraUsuario($_SESSION['user']['id']);
}

