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

    protected function checkUserToken()
    {
        $jwtMngr = new JwtManager();

        $decodedTkn = $jwtMngr->decodeToken(self::getBtoken());

        if(!is_object($decodedTkn) && empty($decodedTkn[0]) && isset($decodedTkn["error"]))
        {
            self::createUnauthorizedResponse();

            self::echoMsgWithExit($decodedTkn);
        }

        $tmp = $this->dbAccess->table;
        $this->dbAccess->table = 'users';
        $res = $this->dbAccess->getResultByOneParam("email", $decodedTkn->email);
        $this->dbAccess->table = $tmp;

        if(empty($res[0]))
        {
            self::createUnauthorizedResponse();

            self::echoMsgWithExit(["status" => 0, "msg" => "No user with email " . $decodedTkn->email . " not found!", 'sys_msg' => $res[1] ?? null]);

            exit();
        }

        return [true, "email" => $decodedTkn->email, "id" => $res[0]["id"]];
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

    protected function createBadReqResponse(string $msg)
    {
        header('HTTP/1.1 400 Bad Request');

        self::returnInfoMsg($msg);
    }

    protected function createNotFoundResponse($msg = "Not Found")
    {
        header('HTTP/1.1 404 Not Found');

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