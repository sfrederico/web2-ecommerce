<?php

include_once(__DIR__ . '/../model/Usuario.php');

class UsuarioDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function createUsuario(Usuario $usuario): bool {
        $query = "INSERT INTO Usuario (nomeUsuario, senha, nome, papel) VALUES (:nomeUsuario, :senha, :nome, :papel)";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            ':nomeUsuario' => $usuario->getNomeUsuario(),
            ':senha' => $usuario->getSenha(),
            ':nome' => $usuario->getNome(),
            ':papel' => $usuario->getPapel(),
        ]);
    }

    public function getUsuarioById(int $id): ?Usuario {
        $query = "SELECT * FROM Usuario WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nomeUsuario'],
                $row['senha'],
                $row['nome'],
                $row['papel']
            );
        }
        return null;
    }

    public function getUsuarioByNomeUsuario(string $nomeUsuario): ?Usuario {
        $query = "SELECT * FROM Usuario WHERE nomeUsuario = :nomeUsuario";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':nomeUsuario' => $nomeUsuario]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nomeUsuario'],
                $row['senha'],
                $row['nome'],
                $row['papel']
            );
        }
        return null;
    }

    // Additional methods for update and delete can be added here
}
?>