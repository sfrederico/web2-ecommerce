<?php

require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../dao/UsuarioDao.php';
require_once __DIR__ . '/../dao/FornecedorDao.php';
require_once __DIR__ . '/../dao/EstoqueDao.php';
require_once __DIR__ . '/../model/Estoque.php';

class ProdutoService {
    private ProdutoDao $produtoDao;
    private UsuarioDao $usuarioDao;
    private FornecedorDao $fornecedorDao;
    private EstoqueDao $estoqueDao;

    public function __construct($dbConnection) {
        $this->produtoDao = new ProdutoDao($dbConnection);
        $this->usuarioDao = new UsuarioDao($dbConnection);
        $this->fornecedorDao = new FornecedorDao($dbConnection, $this->usuarioDao);
        $this->estoqueDao = new EstoqueDao($dbConnection);
    }

    public function criarProduto(array $dados): int {
        $fornecedor = $this->fornecedorDao->getFornecedorById($dados['fornecedorId']);

        if (!$fornecedor) {
            throw new Exception("Fornecedor não encontrado.");
        }

        // Criação do produto
        $produto = new Produto($dados['nome'], $dados['descricao']);
        $produto->setFornecedor($fornecedor);
        $produtoId = $this->produtoDao->create($produto);
        $produto->setId($produtoId);

        // Criação do estoque associado ao produto
        $estoque = new Estoque($dados['quantidade'], $dados['preco']);
        $estoque->setProduto($produto);
        $this->estoqueDao->create($estoque);

        return $produtoId;
    }

    public function buscarProdutoPorId(int $id): ?Produto {
        return $this->produtoDao->getProdutoById($id);
    }

    public function atualizarProduto(Produto $produto): void {
        $this->produtoDao->update($produto);
    }
}