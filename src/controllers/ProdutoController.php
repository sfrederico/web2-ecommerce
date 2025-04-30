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

    public function editaProduto($id) {
        $produto = $this->produtoDao->getProdutoById($id);
        if ($produto) {
            include __DIR__ . '/../views/produto/editar.php';
        }
    }

    public function atualizaProduto($id) {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];

        $produto = new Produto($nome, $descricao);
        $this->produtoDao->atualizarProduto($id, $produto);

        header("Location: /produtos.php?success=1");
    }

    public function excluiProduto($id) {
        $this->produtoDao->deletarProduto($id);
        header("Location: /produtos.php?success=1");
    }
}