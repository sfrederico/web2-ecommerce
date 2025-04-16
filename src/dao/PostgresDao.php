<?php

class PostgresDao {

    private $host;
    private $db_name;
    private $port;
    private $username;
    private $password;
    public $connection;

    public function __construct() {
        $this->host = getenv('DB_HOST');
        $this->db_name = getenv('DB_NAME');
        $this->port = getenv('DB_PORT');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASSWORD');
    }

    // Get the database connection
    public function getConnection() {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}",
                    $this->username,
                    $this->password
                );
            } catch (PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
            }
        }
        return $this->connection;
    }
}
?>