<?php
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../dao/ClienteDao.php';
require_once __DIR__ . '/../dao/FornecedorDao.php';

class PerfilService {
    private UsuarioDao $usuarioDao;
    private ClienteDao $clienteDao;
    private FornecedorDao $fornecedorDao;

    public function __construct($dbConnection) {
        $this->usuarioDao = new UsuarioDao($dbConnection);
        $this->clienteDao = new ClienteDao($dbConnection, $this->usuarioDao);
        $this->fornecedorDao = new FornecedorDao($dbConnection, $this->usuarioDao);
    }

    public function obterPerfil(int $id): ?Usuario {
        return $this->usuarioDao->getUsuarioById($id);
    }

    public function obterCliente(int $id): ?Cliente {
        return $this->clienteDao->getClienteById($id);
    }

    public function obterFornecedor(int $id): ?Fornecedor {
        return $this->fornecedorDao->getFornecedorById($id);
    }

    public function atualizarPerfil(int $id, array $dados): void {
        $usuario = $this->usuarioDao->getUsuarioById($id);

        if (!$usuario) {
            throw new Exception("Usuário não encontrado.");
        }

        $usuario->setNome($dados['nome']);
        $usuario->setNomeUsuario($dados['nomeUsuario']);
        $usuario->setSenha($dados['senha']);
        $usuario->setEmail($dados['email']);
        $usuario->setTelefone($dados['telefone']);
        $this->usuarioDao->update($usuario);

        if ($usuario->getPapel() === 'cliente') {
            $cliente = $this->clienteDao->getClienteById($id);
            $cliente->setCartaoCredito($dados['cartaoCredito']);
            $this->clienteDao->update($cliente);
        } elseif ($usuario->getPapel() === 'fornecedor') {
            $fornecedor = $this->fornecedorDao->getFornecedorById($id);
            $fornecedor->setDescricao($dados['descricao']);
            $this->fornecedorDao->update($fornecedor);
        }
    }

    public function deletarUsuario(int $id): void {
        $this->usuarioDao->delete($id);
    }
}