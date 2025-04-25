<?php
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../dao/ClienteDao.php';
require_once __DIR__ . '/../dao/FornecedorDao.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../model/Cliente.php';
require_once __DIR__ . '/../model/Fornecedor.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class PerfilController {
    private $usuarioDao;
    private $clienteDao;
    private $fornecedorDao;

    public function __construct($dbConnection) {
        $this->usuarioDao = new UsuarioDao($dbConnection);
        $this->clienteDao = new ClienteDao($dbConnection, $this->usuarioDao);
        $this->fornecedorDao = new FornecedorDao($dbConnection, $this->usuarioDao);
    }

    public function mostraUsuario($id) {
        $usuario = $this->usuarioDao->getUsuarioById($id);

        if ($usuario->getPapel() == 'cliente') {
            $cliente = $this->clienteDao->getClienteById($id);
        } elseif ($usuario->getPapel() == 'fornecedor') {
            $fornecedor = $this->fornecedorDao->getFornecedorById($id);
        }

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

        $this->usuarioDao->update($usuario);

        $_SESSION['user']['nome'] = $nome;
        $_SESSION['user']['nomeUsuario'] = $nomeUsuario;
        
        header("Location: /perfil.php?success=1");
    }

}