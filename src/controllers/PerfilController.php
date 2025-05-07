<?php
require_once __DIR__ . '/../services/PerfilService.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class PerfilController {
    private PerfilService $perfilService;

    public function __construct($dbConnection) {
        $this->perfilService = new PerfilService($dbConnection);
    }

    public function mostraUsuario($id) {
        $usuario = $this->perfilService->obterPerfil($id);
        $perfilEspecifico = null;

        if ($usuario->getPapel() === 'cliente') {
            $perfilEspecifico = $this->perfilService->obterCliente($id);
        } elseif ($usuario->getPapel() === 'fornecedor') {
            $perfilEspecifico = $this->perfilService->obterFornecedor($id);
        }

        include __DIR__ . '/../views/perfil/perfil.php';
    }

    public function atualizaPerfil($id) {
        try {
            $this->perfilService->atualizarPerfil($id, $_POST);
            $_SESSION['user']['nome'] = $_POST['nome'];
            $_SESSION['user']['nomeUsuario'] = $_POST['nomeUsuario'];
            header("Location: /perfil.php?success=1");
        } catch (Exception $e) {
            header("Location: /perfil.php?error=" . urlencode($e->getMessage()));
        }
    }

    public function deletaUsuario($id) {
        $this->perfilService->deletarUsuario($id);
        header("Location: /logout.php");
    }
}