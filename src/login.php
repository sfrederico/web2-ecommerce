<?php

include_once(__DIR__ . '/init.php');

include_once(__DIR__ . '/controllers/LoginController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginController = new LoginController($dbConnection);
    $loginController->handleLogin();
} else {
    include_once(__DIR__ . '/views/login/login_form.php');
}
?>