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

        // Upload da foto (agora com o id do produto)
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fotoPath = $this->salvarFotoProduto($_FILES['foto'], $dados['fornecedorId'], $produtoId);
            $this->produtoDao->updateFoto($produtoId, $fotoPath);
        }

        // Criação do estoque associado ao produto
        $estoque = new Estoque($dados['quantidade'], $dados['preco']);
        $estoque->setProduto($produto);
        $this->estoqueDao->create($estoque);

        return $produtoId;
    }

    private function salvarFotoProduto(array $file, int $fornecedorId, int $produtoId): string {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $dir = __DIR__ . "/../static/uploads/{$fornecedorId}/produtos/{$produtoId}/";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $nomeUnico = uniqid('foto_', true) . '.' . $ext;
        $destino = $dir . $nomeUnico;
        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new Exception('Erro ao salvar a foto do produto.');
        }
        // Caminho relativo para uso no banco e exibição
        return "/static/uploads/{$fornecedorId}/produtos/{$produtoId}/{$nomeUnico}";
    }

    public function buscarProdutoPorId(int $id): ?Produto {
        $produto = $this->produtoDao->getProdutoById($id);
        $estoque = $this->estoqueDao->getEstoqueByProdutoId($id);
        $produto->setEstoque($estoque);
        return $produto;
    }

    public function atualizarProduto(array $dados): void {
        // Validar se o produto existe
        $produtoExistente = $this->produtoDao->getProdutoById($dados['id']);
        if (!$produtoExistente) {
            throw new Exception("Produto não encontrado.");
        }
        $estoqueExistente = $this->estoqueDao->getEstoqueByProdutoId($dados['id']);
        if (!$estoqueExistente) {
            throw new Exception("Estoque não encontrado.");
        }

        $produto = new Produto($dados['nome'], $dados['descricao']);
        $produto->setId($produtoExistente->getId());

        $estoque = new Estoque($dados['quantidade'], $dados['preco']);
        $estoque->setId($estoqueExistente->getId());

        $this->produtoDao->update($produto);
        $this->estoqueDao->update($estoque);
    }
}