<?php

define("DB", "message_board");
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");

function connect()
{
    $db = DB;
    $host = HOST;
    $user = USER;
    $password = PASSWORD;
    $dsn = "mysql:host=$host;dbname=$db";

    return new PDO($dsn, $user, $password);
}
