<?php

// Incluir inicializações necessárias
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/controllers/ApiController.php';

// Definir cabeçalhos para API REST
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Tratar requisições OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obter a rota da URL
$route = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// Função para retornar resposta JSON
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// Roteamento básico
try {
    // Instanciar o controller com a conexão do banco
    $apiController = new ApiController($dbConnection);
    
    switch ($route) {
        case 'pedidos':
            if ($method === 'GET') {
                // Obter o parâmetro 'termo' da URL
                $termo = $_GET['termo'] ?? null;
                
                $resultado = $apiController->buscarPedidos($termo);
                jsonResponse($resultado);
            }
            break;
            
        default:
            jsonResponse([
                'success' => false,
                'message' => 'Endpoint não encontrado'
            ], 404);
    }
    
} catch (Exception $e) {
    jsonResponse([
        'success' => false,
        'message' => 'Erro interno do servidor: ' . $e->getMessage()
    ], 500);
}
