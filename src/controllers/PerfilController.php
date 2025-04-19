<?php
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class PerfilController {
    private $usuarioDao;

    public function __construct($dbConnection) {
        $this->usuarioDao = new UsuarioDao($dbConnection);
    }

    public function mostraUsuario($id) {
        $usuario = $this->usuarioDao->getUsuarioById($id);
        if ($usuario) {
            include __DIR__ . '/../views/perfil/perfil.php';
        }

    }

    public function atualizaPerfil(
    ) {
        $nome = $_POST['nome'];
        $nomeUsuario = $_POST['nomeUsuario'];
        $senha = $_POST['senha'];

        $usuario = new Usuario(
            $_SESSION['user']['id'],
            $nomeUsuario,
            $senha,
            $nome,
            $_SESSION['user']['papel']
        );

        $this->usuarioDao->updateUsuario($usuario);

        $_SESSION['user']['nome'] = $nome;
        $_SESSION['user']['nomeUsuario'] = $nomeUsuario;
        
        header("Location: /perfil.php?success=1");
    }

}