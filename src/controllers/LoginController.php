<?php

include_once(__DIR__ . '/../dao/UsuarioDao.php');
include_once(__DIR__ . '/../model/Usuario.php');

class LoginController {
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }
    
    private function verifyPassword($inputPassword, $storedPassword) {
        if ($inputPassword === $storedPassword) {
            return true;
        }
        return false;
    }

    public function handleLogin() {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $usuarioDao = new UsuarioDao($this->dbConnection);
        $user = $usuarioDao->getUsuarioByNomeUsuario($username);

        if ($user && $this->verifyPassword($password, $user->getSenha())) {
            session_start();
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'nomeUsuario' => $user->getNomeUsuario(),
                'nome' => $user->getNome(),
                'papel' => $user->getPapel()
            ];
            header("Location: /views/home/lista.php");
            exit();
        } else {
            header("Location: /login.php?error=invalid_credentials");
            exit();
        }
    }
}
?>