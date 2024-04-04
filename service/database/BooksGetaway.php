<?php

namespace database;

use database\PDOException;

class BooksGetaway extends Database
{
    public function getAllResults(): array
    {
        try {
            return $this
                ->connection
                ->query('SELECT * FROM books')
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
                ->query("SELECT * FROM books WHERE $param = $val;")
                ->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e)
        {
            return [false, $e->getMessage()];
        }
    }

    public function update($id, Array $input)
    {
        if(count($input) < 2 || !is_numeric($id))
        {
            return -1;
        }

        $statement = "
            UPDATE books
            SET 
                title = :title,
                genre  = :genre,
                author_id = :author_id,
                realese_date = :realese_date,
                description = :description
            WHERE id = :id;
        ";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'title' => $input['title'],
                'genre'  => $input['genre'],
                'author_id' => $input['author_id'] ?? null,
                'realese_date' => $input['realese_date'] ?? null,
                'description' => $input['description'] ?? null
            ));
            return 1;
        } catch (\PDOException $e) {
            return [false, $e->getMessage()];
        }
    }

    public function create(Array $input)
    {
        if(count($input) < 2)
        {
            return -1;
        }

        $statement = "
            INSERT INTO books 
                (title, genre, author_id, realese_date, description)
            VALUES
                (:title, :genre, :author_id, :realese_date, :description);
        ";

        try {
            $statement = $this
                ->connection
                ->prepare($statement);

            $statement->execute(array(
                'title' => $input['title'],
                'genre'  => $input['genre'],
                'author_id' => $input['author_id'] ?? null,
                'realese_date' => $input['realese_date'] ?? null,
                'description' => $input['description'] ?? null,
            ));

            return 1;
        } catch (\PDOException $e) {
            return [false, $e->getMessage()];
        }
    }

    public function delete($id)
    {
        if(!is_numeric($id))
        {
            return -1;
        }

        $statement = "
            DELETE FROM books
            WHERE id = :id;
        ";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array('id' => $id));

            return 1;
        } catch (\PDOException $e) {
            return [false, $e->getMessage()];
        }
    }
}
