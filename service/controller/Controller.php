<?php
namespace controller;

use database\Database;

class Controller
{
    protected Database $dbAccess;

    public function __construct(Database $a)
    {
        if ($a === null)
        {
            exit("Database class missing");
        }

        $this->dbAccess = $a;
    }

    public function listen(...$args)
    {
        $this->createOkResponse();
    }

    protected function createOkResponse(...$args)
    {
        echo json_encode("ToDo");
    }
}