<?php

abstract class Database
{
    public $connection;

    public function __construct(string $host, string $port, string $dbName, string $user, string $password)
    {
        try {
            $this->connection = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbName",
                $user,
                $password);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    abstract public function getAllResults();
}
