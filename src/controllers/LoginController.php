<?php

include_once(__DIR__ . '/../dao/UsuarioDao.php');
include_once(__DIR__ . '/../model/Usuario.php');
include_once(__DIR__ . '/../init.php');

class LoginController {
    
    public function handleLogin() {
        header("Location: /views/login_success.php");
        // $username = $_POST['username'];
        // $password = $_POST['password'];

        // $usuarioDao = new UsuarioDao($dbConnection);
        // $user = $usuarioDao->getUsuarioByNomeUsuario($username);

        // if ($user && password_verify($password, $user->getSenha())) {
        //     session_start();
        //     $_SESSION['user'] = $user;
        //     header("Location: /views/login_success.php");
        //     exit();
        // } else {
        //     header("Location: /login.php?error=invalid_credentials");
        //     exit();
        // }
    }
}
?>