<?php
require_once __DIR__ . '/../services/ContaService.php';

class ContaController {
    private ContaService $contaService;

    public function __construct($dbConnection) {
        $this->contaService = new ContaService($dbConnection);
    }

    public function handleCreateAccount(array $postData): void {
        try {
            $this->contaService->criarConta($postData);
            header("Location: /login.php?account_created=true");
        } catch (Exception $e) {
            header("Location: /create_account.php?error=" . urlencode($e->getMessage()));
        }
        exit();
    }
}