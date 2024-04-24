<?php

namespace database;

use PDO;

abstract class Database
{
    public $connection;
    public $table = null;

    public function __construct(string $host, string $port, string $dbName, string $user, string $password)
    {
        try {
            $this->connection = new \PDO(
                "mysql:host=$host;port=$port;dbname=$dbName",
                $user,
                $password);
        }
        catch (\PDOException $e)
        {
            exit($e->getMessage());
        }

        if ($this->table === null)
        {
            throw new \Error("Missing Database table name property!", 404);
        }
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function getAllResults(): array
    {
        try {
            return $this
                ->connection
                ->query("SELECT * FROM $this->table")
                ->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e)
        {
            return [false, $e->getMessage()];
        }
    }

    public function getResultByOneParam($param, $val)
    {
        $val = is_numeric($val) ? $val : "'" . $val . "'";

        try {
            return $this
                ->connection
                ->query("SELECT * FROM $this->table WHERE $param = $val;")
                ->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e)
        {
            return [false, $e->getMessage()];
        }
    }

    public function delete($id)
    {
        if(!is_numeric($id))
        {
            return -1;
        }

        $statement = "DELETE FROM $this->table WHERE id = :id;";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array('id' => $id));

            if($statement->rowCount() > 0)
            {
                return 1;
            }

            return 0;
        } catch (\PDOException $e) {
            return [false, $e->getMessage()];
        }
    }

    public function validate($data){

        $data = trim($data);

        $data = stripslashes($data);

        $data = htmlspecialchars($data);

        return $data;
    }
}
