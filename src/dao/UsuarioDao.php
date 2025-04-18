<?php

include_once(__DIR__ . '/../model/Usuario.php');

class UsuarioDao {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function createUsuario(Usuario $usuario): bool {
        $query = "INSERT INTO usuario (nome_usuario, senha, nome, papel) VALUES (:NOME_USUARIO, :SENHA, :NOME, :PAPEL)";
        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            ':NOME_USUARIO' => $usuario->getNomeUsuario(),
            ':SENHA' => $usuario->getSenha(),
            ':NOME' => $usuario->getNome(),
            ':PAPEL' => $usuario->getPapel(),
        ]);
    }

    public function getUsuarioById(int $id): ?Usuario {
        $query = "SELECT * FROM usuario WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':ID', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nome_usuario'],
                $row['senha'],
                $row['nome'],
                $row['papel']
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
                $row['papel']
            );
        }
        return null;
    }

    // Additional methods for update and delete can be added here
}
?>