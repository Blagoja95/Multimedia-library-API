<?php

require 'Database.php';

class BooksGetaway extends Database
{
    public function getAllResults(): array
    {
        try {
            return $this
                ->connection
                ->query('SELECT * FROM books')
                ->fetchAll();

        } catch (PDOException $e)
        {
            return [false, $e->getMessage()];
        }
    }

    public function getResultByOneParam($param, $val)
    {
        try {
            return $this
                ->connection
                ->query("SELECT * FROM books WHERE $param = $val;")
                ->fetchAll();

        } catch (PDOException $e)
        {
            exit($e->getMessage());
        }
    }

    public function update()
    {

    }

    public function create()
    {

    }

    public function delete()
    {

    }
}
