<?php
require 'vendor/autoload.php';

use controller\BooksController as BkCntrl;
use database\BooksGetaway as BkGetW;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// TODO: loader
$dbAccess = new BkGetW(
    $_ENV['MYSQL_HOST'],
    $_ENV['MYSQL_PORT'],
    $_ENV['MYSQL_DB_NAME'],
    $_ENV['MYSQL_USERNAME'],
    $_ENV['MYSQL_PASSWORD']);

$path = [
    "books" => new BkCntrl($dbAccess),

    "songs" => new \controller\Controller($dbAccess),

    "videos" => new \controller\Controller($dbAccess),

    "users" => new \controller\Controller($dbAccess),

    "authors" => new \controller\Controller($dbAccess)
];

if ($path[$uri[1]])
{
    $path[$uri[1]]->listen($uri);
}
else
{
    header("HTTP/1.1 404 Not Found");
    exit();
}