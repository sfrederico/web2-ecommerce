CREATE TABLE Usuario (
    id SERIAL PRIMARY KEY,
    nomeUsuario VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    papel VARCHAR(255) NOT NULL
);

-- Insert an admin user
INSERT INTO Usuario (nomeUsuario, senha, nome, papel)
VALUES ('admin', '123123', 'Administrador', 'admin');