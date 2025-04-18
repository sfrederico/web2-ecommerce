<?php

class Usuario {
    private int $id;
    private string $nomeUsuario;
    private string $senha;
    private string $nome;
    private string $papel;

    public function __construct(int $id, string $nomeUsuario, string $senha, string $nome, string $papel) {
        $this->id = $id;
        $this->nomeUsuario = $nomeUsuario;
        $this->senha = $senha;
        $this->nome = $nome;
        $this->papel = $papel;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNomeUsuario(): string {
        return $this->nomeUsuario;
    }

    public function getSenha(): string {
        return $this->senha;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getPapel(): string {
        return $this->papel;
    }

    public function setNomeUsuario(string $nomeUsuario): void {
        $this->nomeUsuario = $nomeUsuario;
    }

    public function setSenha(string $senha): void {
        $this->senha = $senha;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setPapel(string $papel): void {
        $this->papel = $papel;
    }
}