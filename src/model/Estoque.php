<?php

require_once __DIR__ . '/Produto.php';

class Estoque {
    private int $id;
    private ?Produto $produto = null;
    private int $quantidade;
    private float $preco;

    public function __construct(int $quantidade, float $preco, ?Produto $produto = null) {
        $this->quantidade = $quantidade;
        $this->preco = $preco;
        $this->produto = $produto;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getProduto(): ?Produto {
        return $this->produto;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }

    public function getPreco(): float {
        return $this->preco;
    }

    public function setQuantidade(int $quantidade): void {
        $this->quantidade = $quantidade;
    }

    public function setPreco(float $preco): void {
        $this->preco = $preco;
    }

    public function setProduto(?Produto $produto): void {
        $this->produto = $produto;
    }
}