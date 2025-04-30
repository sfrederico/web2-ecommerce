<?php
require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../model/Produto.php';
require_once __DIR__ . '/../services/ProdutoService.php';

class ProdutoController {
    private $produtoDao;
    private ProdutoService $produtoService;

    public function __construct($dbConnection) {
        $this->produtoDao = new ProdutoDao($dbConnection);
        $this->produtoService = new ProdutoService($dbConnection);
    }

    public function listaProdutos() {
        $produtos = $this->produtoDao->getTodosProdutos();
        include __DIR__ . '/../views/produto/lista.php';
    }

    public function mostrarFormularioCriacao() {
        include __DIR__ . '/../views/produto/produto_form.php';
    }

    public function salvarProduto(array $dados) {
        $nome = $dados['nome'];
        $descricao = $dados['descricao'];
        $fornecedorId = $_SESSION['user']['id'];

        $this->produtoService->criarProduto($nome, $descricao, $fornecedorId);

        // Redireciona de volta para a lista de produtos
        header("Location: /estoque.php");
        exit();
    }

    public function editarProduto(int $id) {
        $produto = $this->produtoService->buscarProdutoPorId($id);

        if (!$produto) {
            throw new Exception("Produto não encontrado.");
        }

        include __DIR__ . '/../views/produto/editar_produto.php';
    }

    public function atualizarProduto(int $id, array $dados) {
        $nome = $dados['nome'];
        $descricao = $dados['descricao'];

        $produto = new Produto($nome, $descricao);
        $produto->setId($id);

        $this->produtoService->atualizarProduto($produto);

        // Redireciona de volta para a lista de produtos ou estoque
        header("Location: /estoque.php");
        exit();
    }

    public function excluiProduto($id) {
        $this->produtoDao->deletarProduto($id);
        header("Location: /produtos.php?success=1");
    }
}