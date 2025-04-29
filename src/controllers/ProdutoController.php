<?php
require_once __DIR__ . '/../dao/ProdutoDao.php';
require_once __DIR__ . '/../model/Produto.php';

class ProdutoController {
    private $produtoDao;

    public function __construct($dbConnection) {
        $this->produtoDao = new ProdutoDao($dbConnection);
    }

    public function listaProdutos() {
        $produtos = $this->produtoDao->getTodosProdutos();
        include __DIR__ . '/../views/produto/lista.php';
    }

    public function criaProduto() {
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
    
            $produto = new Produto($nome, $descricao);
    
            if ($this->produtoDao->salvarProduto($produto)) {
                header("Location: /src/views/produto/lista.php");
                exit();
            } else {
                header("Location: /src/views/produto/produto_form.php?error=1");
                exit();
            }
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