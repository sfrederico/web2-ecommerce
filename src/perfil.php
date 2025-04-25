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
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $controller->deletaUsuario($_SESSION['user']['id']);
    } else {
        $controller->atualizaPerfil($_SESSION['user']['id']);
    }
} else {
    $controller->mostraUsuario($_SESSION['user']['id']);
}

