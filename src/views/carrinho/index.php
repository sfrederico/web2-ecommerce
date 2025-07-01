<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <div class="container my-5">
        <h1 class="text-center mb-4">Carrinho</h1>
        
        <!-- Botão de teste para adicionar produto -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Teste - Adicionar Produto</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/carrinho.php">
                            <input type="hidden" name="produto_id" value="1">
                            <input type="hidden" name="quantidade" value="2">
                            <input type="hidden" name="preco_unitario" value="29.99">
                            <button type="submit" class="btn btn-primary">
                                Adicionar Produto ID 1 ao Carrinho (Teste)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>