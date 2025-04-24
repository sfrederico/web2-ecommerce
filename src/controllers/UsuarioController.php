<?php
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController {
    private UsuarioDao $usuarioDao;

    public function __construct($dbConnection) {
        $this->usuarioDao = new UsuarioDao($dbConnection);
    }

    public function createUsuario(string $nomeUsuario, string $senha, string $nome, string $papel, string $telefone, string $email): int {
        $usuario = new Usuario(0, $nomeUsuario, $senha, $nome, $papel, $telefone, $email);
        return $this->usuarioDao->create($usuario);
    }
}
