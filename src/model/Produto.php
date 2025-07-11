<?php

require_once __DIR__ . '/Fornecedor.php';

class Produto {
    private int $id;
    private string $nome;
    private string $descricao;
    private Fornecedor $fornecedor;
    private int $fornecedorId;
    private ?Estoque $estoque = null;
    private ?string $foto = null;

    public function __construct(string $nome, string $descricao) {
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    public function getFornecedor(): Fornecedor {
        return $this->fornecedor;
    }

    public function getFornecedorId(): int {
        return $this->fornecedorId;
    }

    public function getEstoque(): ?Estoque {
        return $this->estoque;
    }

    public function getFoto(): ?string {
        return $this->foto;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setFornecedor(Fornecedor $fornecedor): void {
        $this->fornecedor = $fornecedor;
    }

    public function setFornecedorId(int $fornecedorId): void {
        $this->fornecedorId = $fornecedorId;
    }
    
    public function setEstoque(Estoque $estoque): void {
        $this->estoque = $estoque;
    }

    public function setFoto(?string $foto): void {
        $this->foto = $foto;
    }
}