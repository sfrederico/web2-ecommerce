CREATE TABLE produto (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    fornecedor_id INT NOT NULL REFERENCES fornecedor(id) ON DELETE CASCADE
);