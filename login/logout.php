<?php
session_start();
session_destroy();
require_once "../utils.php";

$success = "Deslogado com sucesso!";
httpResponseExit ([
    'headers' => [
        'Content-type:application/json',
        'charset=utf-8'
    ],
    'body' => $success,
    'code' => 200
]);