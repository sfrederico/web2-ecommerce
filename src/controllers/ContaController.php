<?php
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/ClienteController.php';
require_once __DIR__ . '/../controllers/FornecedorController.php';
require_once __DIR__ . '/../model/Cliente.php';
require_once __DIR__ . '/../model/Fornecedor.php';

class ContaController {
    private UsuarioController $usuarioController;
    private ClienteController $clienteController;
    private FornecedorController $fornecedorController;

    public function __construct($dbConnection) {
        $this->usuarioController = new UsuarioController($dbConnection);
        $this->clienteController = new ClienteController($dbConnection);
        $this->fornecedorController = new FornecedorController($dbConnection);
    }

    public function handleCreateAccount(array $postData): void {
        $nomeUsuario = $postData['nomeUsuario'];
        $senha = $postData['senha'];
        $nome = $postData['nome'];
        $papel = $postData['papel'];
        $telefone = $postData['telefone'];
        $email = $postData['email'];

        // Create the user
        $usuario = $this->usuarioController->createUsuario($nomeUsuario, $senha, $nome, $papel, $telefone, $email);

        $cliente = false;
        $fonecedor = false;
        // Handle specific roles
        if ($papel === 'cliente') {
            $cartaoCredito = $postData['cartaoCredito'];
            $cliente = $this->clienteController->createCliente($usuario, $cartaoCredito);
        } elseif ($papel === 'fornecedor') {
            $descricao = $postData['descricao'];
            $fornecedor = $this->fornecedorController->createFornecedor($usuario, $descricao);
        }

        if ($usuario && ($cliente || $fornecedor)) {
            header("Location: /login.php?account_created=true");
        }
    }
}