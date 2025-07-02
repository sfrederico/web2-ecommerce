<?php

require_once __DIR__ . '/Produto.php';

class ItemPedido {
    private int $id;
    private int $pedidoId;
    private int $produtoId;
    private Produto $produto;
    private int $quantidade;
    private float $precoUnitario;
    private float $subtotal;

    public function __construct(int $pedidoId, int $produtoId, int $quantidade, float $precoUnitario) {
        $this->pedidoId = $pedidoId;
        $this->produtoId = $produtoId;
        $this->quantidade = $quantidade;
        $this->precoUnitario = $precoUnitario;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getPedidoId(): int {
        return $this->pedidoId;
    }

    public function setPedidoId(int $pedidoId): void {
        $this->pedidoId = $pedidoId;
    }

    public function getProdutoId(): int {
        return $this->produtoId;
    }

    public function setProdutoId(int $produtoId): void {
        $this->produtoId = $produtoId;
    }

    public function getProduto(): Produto {
        return $this->produto;
    }

    public function setProduto(Produto $produto): void {
        $this->produto = $produto;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): void {
        $this->quantidade = $quantidade;
        $this->calcularSubtotal();
    }

    public function getPrecoUnitario(): float {
        return $this->precoUnitario;
    }

    public function setPrecoUnitario(float $precoUnitario): void {
        $this->precoUnitario = $precoUnitario;
        $this->calcularSubtotal();
    }

    public function getSubtotal(): float {
        return $this->subtotal;
    }

    public function calcularSubtotal(): void {
        $this->subtotal = $this->quantidade * $this->precoUnitario;
    }

    public function aumentarQuantidade(int $quantidade = 1): void {
        $this->quantidade += $quantidade;
        $this->calcularSubtotal();
    }

    public function diminuirQuantidade(int $quantidade = 1): void {
        if ($this->quantidade > $quantidade) {
            $this->quantidade -= $quantidade;
            $this->calcularSubtotal();
        }
    }
}
