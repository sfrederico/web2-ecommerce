<?php

include_once(__DIR__ . '/../model/Usuario.php');

class UsuarioDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function create(Usuario $usuario): int {
        $query = "INSERT INTO usuario (nome_usuario, senha, nome, papel, telefone, email) VALUES (:NOME_USUARIO, :SENHA, :NOME, :PAPEL, :TELEFONE, :EMAIL) RETURNING id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            ':NOME_USUARIO' => $usuario->getNomeUsuario(),
            ':SENHA' => $usuario->getSenha(),
            ':NOME' => $usuario->getNome(),
            ':PAPEL' => $usuario->getPapel(),
            ':TELEFONE' => $usuario->getTelefone(),
            ':EMAIL' => $usuario->getEmail(),
        ]);

        return (int) $stmt->fetchColumn();
    }
    
    public function update(Usuario $usuario): bool {
        $query = "UPDATE usuario SET nome_usuario = :NOME_USUARIO, senha = :SENHA, nome = :NOME, papel = :PAPEL, telefone = :TELEFONE, email = :EMAIL WHERE id = :ID";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            ':ID' => $usuario->getId(),
            ':NOME_USUARIO' => $usuario->getNomeUsuario(),
            ':SENHA' => $usuario->getSenha(),
            ':NOME' => $usuario->getNome(),
            ':PAPEL' => $usuario->getPapel(),
            ':TELEFONE' => $usuario->getTelefone(),
            ':EMAIL' => $usuario->getEmail(),
        ]);
    }

    public function getUsuarioById(int $id): ?Usuario {
        $query = "SELECT * FROM usuario WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nome_usuario'],
                $row['senha'],
                $row['nome'],
                $row['papel'],
                $row['telefone'],
                $row['email']
            );
        }
        return null;
    }

    public function getUsuarioByNomeUsuario(string $nomeUsuario): ?Usuario {
        $query = "SELECT * FROM usuario WHERE nome_usuario = :nomeUsuario";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':nomeUsuario', $nomeUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nome_usuario'],
                $row['senha'],
                $row['nome'],
                $row['papel'],
                $row['telefone'],
                $row['email']
            );
        }
        return null;
    }

    // Additional methods for update and delete can be added here
}
?>