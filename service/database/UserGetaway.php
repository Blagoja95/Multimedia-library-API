<?php

namespace database;

class UserGetaway extends Database
{
    protected $table = "users";

    public function update($input)
    {
        if(!property_exists($input, "id"))
            return -1;

        $prof_true = property_exists($input, "profile_img_id");
        $about_true = property_exists($input, "about");

        $profile = $prof_true ? ",profile_img_id = ?" : "";
        $about = $about_true ? ",about = ?" : "";

        $statement = "
            UPDATE $this->table 
            SET 
            fname = ?,
            lname  = ?"
            . $profile
            . $about
            . "WHERE id = ?;";


        try {
            $statement = $this->connection->prepare($statement);

            $statement->bindValue(1, $input->fname);
            $statement->bindValue(2, $input->lname);
            $statement->bindValue(5,  $input->id);

            if($prof_true)
                $statement->bindValue(3, $input->profile_img_id);

            if($about_true)
                $statement->bindValue(4, $input->about);

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

    public function create()
    {
        $statement = "
            INSERT INTO $this->table 
                (fname, lname, email, password, profile_img_id, about)
            VALUES
                (?, ?, ?, ?, ?, ?);";

        try {
            $statement = $this
                ->connection
                ->prepare($statement);

            $statement->bindValue(1, $this->validate($_POST['fname']));
            $statement->bindValue(2, $this->validate($_POST['lname']));
            $statement->bindValue(3, $this->validate($_POST['email']));
            $statement->bindValue(4, password_hash($this->validate($_POST['password']), PASSWORD_BCRYPT, ["cost" => 12]));
            $statement->bindValue(5, isset($_POST['profile_img_id']) ? $this->validate($_POST['profile_img_id']) : null);
            $statement->bindValue(6, isset($_POST['about']) ? $this->validate($_POST['about']) : null);

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