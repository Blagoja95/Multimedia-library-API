<?php
namespace controller;

class BooksController extends Controller
{
   public function listen(...$args)
   {
       switch ($_SERVER["REQUEST_METHOD"]) {
           case 'GET':
               $this->handleRead($args[0][2] ?? '', $args[0][3] ?? '');
               break;

           case 'POST':
               $this->handleCreate();
               break;

           case 'PUT':
               $this->handleUpdate();
               break;

           case 'DELETE':
               $this->handleDelete();
               break;

           default:
               $this->notFoundResponse();
               break;
       }
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
            $this->createBadReqResponse($res[1]);
        }
        else
        {
            $this->createNoContentResponse();
        }

       exit();
   }

    protected function handleCreate()
    {
        $d = self::checkUserToken();

        $body = json_decode(file_get_contents("php://input"));
        $body->creator_id = $d["id"];

        $res = $this->dbAccess->create($body);

        if(empty($res[0]))
        {
            self::createUnauthorizedResponse();

            self::echoMsgWithExit(["status" => 0, "msg" => "Something went wrong!", "sys_msg" => $res[1]]);

            exit();
        }

        self::createOkResponse($res);
    }

    private function handleUpdate()
    {
        $d = self::checkUserToken();

        $body = json_decode(file_get_contents("php://input"));

        $res = $this->dbAccess->update($body);

        if(empty($res[0]))
        {
            self::createUnauthorizedResponse();

            self::echoMsgWithExit(["status" => 0, "msg" => "Something went wrong!", "sys_msg" => $res[1] ?? null]);

            exit();
        }

        self::createOkResponse($res);
    }

    private function handleDelete()
    {
        $d = self::checkUserToken();

        $body = json_decode(file_get_contents("php://input"));

        if(!isset($body->id))
        {
            self::createUnauthorizedResponse();

            self::echoMsgWithExit(["msg" => "Missing books id!"]);
        }

        $fbook = $this->dbAccess->getResultByOneParam("id", $body->id);

        if(empty($fbook))
        {
            self::createBadReqResponse("Book with id " . $body->id . " not found!");

            exit();
        }

        $res = $this->dbAccess->delete($body->id);

        if(is_numeric($res) && $res === 1)
        {
            $this->createOkResponse(["info" => "Success", "id" => $d["id"]]);
        }

        if(empty($res[0]))
        {
            $this->createBadReqResponse($res[1]);
        }
    }
}