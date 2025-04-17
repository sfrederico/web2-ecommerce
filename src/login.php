<?php

include_once(__DIR__ . '/controllers/LoginController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginController = new LoginController();
    $loginController->handleLogin();
} else {
    include_once(__DIR__ . '/views/login_form.php');
}
?>