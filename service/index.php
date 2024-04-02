<?php
require 'bootstrap.php';
require './database/BooksGetaway.php';

$a = new BooksGetaway(
    getenv('HOST'),
    getenv('MYSQL_PORT'),
    getenv('DB_NAME'),
    getenv('MYSQL_USERNAME'),
    getenv('MYSQL_PASSWORD'));

echo var_dump($a->getAllResults());
echo var_dump($a->getResultByOneParam("author_id", 2));