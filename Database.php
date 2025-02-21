<?php

require_once __DIR__ . '/config.php';

class Database
{
    private $host;
    private $port;
    private $user;
    private $pass;
    private $dbname;
    private $conn;

    public function __construct()
    {
        $this->host = $_ENV['DATABASE_HOST'];
        $this->port = $_ENV['DATABASE_PORT'];
        $this->user = $_ENV['DATABASE_USER'];
        $this->pass = $_ENV['DATABASE_PASS'];
        $this->dbname = $_ENV['DATABASE_NAME'];
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->dbname}",
                $this->user,
                $this->pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}