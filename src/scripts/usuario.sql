CREATE TABLE USUARIO (
    ID SERIAL PRIMARY KEY,
    NOME_USUARIO VARCHAR(255) NOT NULL,
    SENHA VARCHAR(255) NOT NULL,
    NOME VARCHAR(255) NOT NULL,
    PAPEL VARCHAR(255) NOT NULL
);

-- Insert an admin user
INSERT INTO USUARIO (NOME_USUARIO, SENHA, NOME, PAPEL)
VALUES ('admin', '123123', 'Administrador', 'admin');