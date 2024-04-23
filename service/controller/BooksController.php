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
            $this->createNotFoundResponse($res[1]);
        }
        else
        {
            $this->createNoContentResponse();
        }

       exit();
   }

    protected function handleCreate()
    {

    }

    protected function handleUpdate()
    {
    }

    protected function handleDelete()
    {

    }

    protected function notFoundResponse()
    {

    }
}