<?php
require_once __DIR__ . '/Usuario.php';

class Fornecedor {
    private Usuario $usuario;
    private string $descricao;

    public function __construct(Usuario $usuario, string $descricao) {
        $this->usuario = $usuario;
        $this->descricao = $descricao;
    }

    public function getUsuario(): Usuario {
        return $this->usuario;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }
}