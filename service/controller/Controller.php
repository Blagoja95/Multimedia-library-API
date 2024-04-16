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

    protected function createOkResponse(array $res = [])
    {
        header('HTTP/1.1 200 OK');

        echo json_encode([
            "size" => sizeof($res),
            "data" => $res
        ]);
    }

    protected function createNotFoundResponse(string $msg)
    {
        header('HTTP/1.1 400	Bad Request');

        echo json_encode(["info" => $msg]);
    }

    protected function createNoContentResponse()
    {
        header('HTTP/1.1 204 No Content');
    }

    protected function createUnauthorizedResponse()
    {
        header("HTTP/1.1 401 Unauthorized");
    }

    protected function createForbiddenResponse()
    {
        header("HTTP/1.1 403 Forbidden");
    }
}