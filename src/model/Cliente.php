<?php
require_once __DIR__ . '/Usuario.php';

class Cliente {
    private Usuario $usuario;
    private string $cartaoCredito;

    public function __construct(Usuario $usuario, string $cartaoCredito) {
        $this->usuario = $usuario;
        $this->cartaoCredito = $cartaoCredito;
    }

    public function getUsuario(): Usuario {
        return $this->usuario;
    }

    public function getCartaoCredito(): string {
        return $this->cartaoCredito;
    }

    public function setCartaoCredito(string $cartaoCredito): void {
        $this->cartaoCredito = $cartaoCredito;
    }
}