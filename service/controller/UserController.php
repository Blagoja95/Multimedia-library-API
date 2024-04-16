<?php

namespace controller;

class UserController extends Controller
{
    public function listen(...$args)
    {
        switch ($_SERVER["REQUEST_METHOD"]) {
            case 'GET':
                $this->handleRead($args[0][2] ?? '', $args[0][3] ?? '');
                break;

            case 'POST':
                $this->handlePost($args[0][2] ?? '', $args[0][3] ?? '');
                break;

            case 'PUT':
                $this->handleUpdate();
                break;

            case 'DELETE':
                $this->handleDelete();
                break;

            default:
                $this->createNotFoundResponse();
                break;
        }
    }

    private function handlePost($path1 = '', $path2 = '')
    {
        switch ($path1)
        {
            case "create":
                $this->createUser();
                break;

            case "login":
                $this->loginUser();
                break;

            default;
                $this->createNotFoundResponse();
                break;
        }

    }

    private function loginUser()
    {
        $this->validateCredentials();

        $user = $this->dbAccess->getResultByOneParam("email", $this->dbAccess->validate($_POST["email"]));

        if (count($user) === 0)
        {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => 0, "msg" => "No user with email " . $_POST["email"] . " found!"]);
        }

        $user = $user[0];

        if (!password_verify($this->dbAccess->validate($_POST['password']), $user["password"]))
        {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => 0, "msg" => "Wrong password!"]);
        }

        unset($user["password"]);

        //TODO add JWT prop
        $this->createOkResponse([$user]);
    }

    private function createUser()
    {
        $this->validateName();
        $this->validateCredentials();

        $res = $this->dbAccess->create();

        unset($res[0]["password"]);

        $this->createOkResponse($res);
    }

    private function validateCredentials()
    {
        if (!isset($_POST["email"]) || !isset($_POST["password"])) {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Missing parameters"]);
        }

        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Invalid email"]);
        }

        if (strlen($_POST["password"]) <= 8) {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Password length must be minimum 8 characters"]);
        }
        elseif (!preg_match("#[0-9]+#", $_POST["password"]))
        {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Password must contain at least 1 number"]);
        }
        elseif (!preg_match("#[A-Z]+#", $_POST["password"]))
        {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Password must contain at least 1 upper case character"]);
        }
        elseif (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["password"]))
        {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Password must contain at least 1 special character"]);
        }
    }

    private function validateName()
    {
        if (!isset($_POST["fname"]) || !isset($_POST["lname"]))
        {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Missing parameters"]);
        }

        if(strlen($_POST["fname"]) <= 2 || strlen($_POST["lname"]) <= 2)
        {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Parameter must be 2 or more characters long"]);
        }

        if(strlen($_POST["fname"]) > 255 || strlen($_POST["lname"]) > 255)
        {
            $this->createUnauthorizedResponse();

            $this->echoMsgWithExit(["status" => "error", "message" => "Parameter can't be more then 255 characters long"]);
        }
    }
    private function echoMsgWithExit($array)
    {
        echo json_encode($array);

        exit();
    }

    public function handleRead(string $path1 = '', string $path2 = '')
    {
        $res = [];

        if(is_numeric($path1))
        {
            $res = $this->dbAccess->getResultByOneParam("id", $path1);
        }
        else if (strlen($path1) > 0 && strlen($path2) > 0)
        {
            $res = $this->dbAccess->getResultByOneParam($path1, urldecode($path2));
        }
        else
        {
            $res = $this->dbAccess->getAllResults();
        }

        $notEmpty = sizeof($res) !== 0;

        if($notEmpty && $res[0] !== false)
        {
            $this->createOkResponse($res);
        }
        else if ($notEmpty && $res[0] === false)
        {
            $this->createNotFoundResponse($res[1]);
        }
        else
        {
            $this->createNoContentResponse();
        }

        exit();
    }
}