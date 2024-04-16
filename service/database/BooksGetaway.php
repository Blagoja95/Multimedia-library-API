<?php

namespace database;

use database\PDOException;

class BooksGetaway extends Database
{
    protected $table = "books";

    public function update($id, Array $input)
    {
        if(count($input) < 2 || !is_numeric($id))
        {
            return -1;
        }

        $statement = "
            UPDATE $this->table 
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
            INSERT INTO $this->table 
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
}
