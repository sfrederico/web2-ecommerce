<?php

require_once __DIR__ . '/Cliente.php';
require_once __DIR__ . '/ItemPedido.php';

class Pedido {
    private int $id;
    private string $numero;
    private Cliente $cliente;
    private int $clienteId;
    private ?string $dataPedido;
    private ?string $dataEntrega;
    private string $situacao;
    private bool $confirmado;
    private float $valorTotal;
    private array $itens = [];

    public function __construct(string $numero, int $clienteId) {
        $this->numero = $numero;
        $this->clienteId = $clienteId;
        $this->situacao = 'PENDENTE';
        $this->confirmado = false;
        $this->valorTotal = 0.00;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getNumero(): string {
        return $this->numero;
    }

    public function setNumero(string $numero): void {
        $this->numero = $numero;
    }

    public function getCliente(): Cliente {
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente): void {
        $this->cliente = $cliente;
    }

    public function getClienteId(): int {
        return $this->clienteId;
    }

    public function setClienteId(int $clienteId): void {
        $this->clienteId = $clienteId;
    }

    public function getDataPedido(): string {
        return $this->dataPedido;
    }

    public function setDataPedido(string $dataPedido): void {
        $this->dataPedido = $dataPedido;
    }

    public function getDataEntrega(): ?string {
        return $this->dataEntrega;
    }

    public function setDataEntrega(?string $dataEntrega): void {
        $this->dataEntrega = $dataEntrega;
    }

    public function getSituacao(): string {
        return $this->situacao;
    }

    public function setSituacao(string $situacao): void {
        $this->situacao = $situacao;
    }

    public function isConfirmado(): bool {
        return $this->confirmado;
    }

    public function setConfirmado(bool $confirmado): void {
        $this->confirmado = $confirmado;
    }

    public function getValorTotal(): float {
        return $this->valorTotal;
    }

    public function setValorTotal(float $valorTotal): void {
        $this->valorTotal = $valorTotal;
    }

    public function getItens(): array {
        return $this->itens;
    }

    public function setItens(array $itens): void {
        $this->itens = $itens;
    }

    public function adicionarItem(ItemPedido $item): void {
        $this->itens[] = $item;
        $this->calcularValorTotal();
    }

    public function removerItem(int $produtoId): void {
        $this->itens = array_filter($this->itens, function(ItemPedido $item) use ($produtoId) {
            return $item->getProdutoId() !== $produtoId;
        });
        $this->calcularValorTotal();
    }

    private function calcularValorTotal(): void {
        $total = 0.00;
        foreach ($this->itens as $item) {
            $total += $item->getSubtotal();
        }
        $this->valorTotal = $total;
    }

    public function confirmarPedido(): void {
        $this->confirmado = true;
        $this->situacao = 'CONFIRMADO';
    }
}
