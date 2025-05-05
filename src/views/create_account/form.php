<?php
if (!isset($error)) {
    $error = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php require_once __DIR__ . '/../comum/header.php'; ?>
    <h1>Create Account</h1>
    <?php if ($error): ?>
        <p style="color: red;">Error: <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="create_account.php" method="POST">
        <label for="nomeUsuario">Username:</label>
        <input type="text" id="nomeUsuario" name="nomeUsuario" required><br>

        <label for="senha">Password:</label>
        <input type="password" id="senha" name="senha" required><br>

        <label for="nome">Name:</label>
        <input type="text" id="nome" name="nome" required><br>

        
        <label for="telefone">Phone:</label>
        <input type="text" id="telefone" name="telefone" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="papel">Role:</label>
        <select id="papel" name="papel" required>
            <option value="cliente">Client</option>
            <option value="fornecedor">Supplier</option>
        </select><br>

        <button id="create-account-submit-btn" type="submit">Create Account</button>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleField = document.getElementById('papel');
            const form = document.querySelector('form');

            function addDynamicField(role) {
                // Remove existing dynamic fields
                const existingDynamicField = document.getElementById('dynamic-field');
                if (existingDynamicField) {
                    existingDynamicField.remove();
                }

                // Add new field based on role selection
                const submitButton = document.getElementById("create-account-submit-btn");
                if (role === 'fornecedor') {
                    const descriptionField = document.createElement('div');
                    descriptionField.id = 'dynamic-field';
                    descriptionField.innerHTML = `
                        <label for="descricao">Description:</label>
                        <input type="text" id="descricao" name="descricao" required>
                    `;
                    submitButton.parentNode.insertBefore(descriptionField, submitButton);
                } else if (role === 'cliente') {
                    const creditCardField = document.createElement('div');
                    creditCardField.id = 'dynamic-field';
                    creditCardField.innerHTML = `
                        <label for="cartaoCredito">Credit Card:</label>
                        <input type="text" id="cartaoCredito" name="cartaoCredito" required>
                    `;
                    submitButton.parentNode.insertBefore(creditCardField, submitButton);
                }
            }

            // Initialize with default value
            addDynamicField(roleField.value);

            roleField.addEventListener('change', function () {
                addDynamicField(roleField.value);
            });
        });
    </script>
</body>
</html>