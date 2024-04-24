<?php

namespace database;

use database\PDOException;

class BooksGetaway extends Database
{
    public $table = "books";

    public function update($input)
    {
        if(!property_exists($input, "id"))
            return -1;

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

            $statement->bindValue('title', $input->title);
            $statement->bindValue('genre', $input->genre);
            $statement->bindValue('author_id', $input->author_id ?? null);
            $statement->bindValue('realese_date', $input->realese_date ?? null);
            $statement->bindValue('description', $input->description ?? null);
            $statement->bindValue('id', $input->id);

            $status = $statement->execute();

            if ($status)
            {
                return $this->getResultByOneParam("id", $input->id);
            }
            else
            {
                return [false, "Error fetching user updated information."];
            }

        } catch (\PDOException $e) {
            return [false, $e->getMessage()];
        }
    }

    public function create($input)
    {
        $statement = "
            INSERT INTO $this->table 
                (creator_id, title, genre, author_id, realese_date, description)
            VALUES
                (:creator_id, :title, :genre, :author_id, :realese_date, :description);
        ";

        try {
            $statement = $this
                ->connection
                ->prepare($statement);

            $statement->bindValue('creator_id', $input->creator_id);
            $statement->bindValue('title', $input->title);
            $statement->bindValue('genre', $input->genre);
            $statement->bindValue('author_id', $input->author_id ?? null);
            $statement->bindValue('realese_date', $input->realese_date ?? null);
            $statement->bindValue('description', $input->description ?? null);

            $status = $statement->execute();

            if($status)
            {
                $id = $this->connection->query("SELECT LAST_INSERT_ID()")->fetchAll(\PDO::FETCH_ASSOC);

                return $this->getResultByOneParam("id", $id[0]["LAST_INSERT_ID()"]);
            }
            else
            {
                return [false, "Error fetching newly created user information."];
            }
        } catch (\PDOException $e) {
            return [false, $e->getMessage()];
        }
    }
}
