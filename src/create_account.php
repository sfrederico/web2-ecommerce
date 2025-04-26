<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/controllers/ContaController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contaController = new ContaController($dbConnection);
    $contaController->handleCreateAccount($_POST);
} else {
    include_once(__DIR__ . '/views/create_account/form.php');
}
?>