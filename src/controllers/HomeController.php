<?php

require_once __DIR__ . '/../services/HomeService.php';

class HomeController {
    private HomeService $homeService;

    public function __construct($dbConnection) {
        $this->homeService = new HomeService($dbConnection);
    }

    public function listarProdutos() {
        $search = $_GET['search'] ?? null;

        if (empty($search)) {
            $produtos = $this->homeService->buscarTodosProdutos();
        } else {
            $produtos = $this->homeService->buscarTodosProdutosPorFiltro($search);
        }
        include __DIR__ . '/../views/home/lista.php';
    }
}


