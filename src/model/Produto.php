<?php

class Produto {
    private string $nome;
    private string $descricao;

    public function __construct(string $nome, string $descricao) {
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }
}