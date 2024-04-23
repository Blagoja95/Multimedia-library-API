<?php
namespace controller;

use database\Database;
use security\JwtManager;

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

    protected function checkBtokenCookie()
    {
        if(empty($_COOKIE["btoken"]))
        {
            self::createForbiddenResponse();

            self::returnInfoMsg("User must be logged in!");
            exit();
        }
    }

    protected function getBtoken()
    {
        $this->checkBtokenCookie();

        return $_COOKIE["btoken"];
    }

    protected function createOkResponse(array $res = [], bool $data = true)
    {
        header('HTTP/1.1 200 OK');

        if ($data)
        {
            echo json_encode([
                "size" => sizeof($res),
                "data" => $res
            ]);

            exit(0);
        }

        echo json_encode($res);
    }

    protected function createNotFoundResponse(string $msg)
    {
        header('HTTP/1.1 400 Bad Request');

        self::returnInfoMsg($msg);
    }

    protected function echoMsgWithExit($array)
    {
        echo json_encode($array);

        exit();
    }

    protected function returnInfoMsg(string $msg)
    {
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