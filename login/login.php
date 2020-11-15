<?php
session_start();
require_once "../utils.php";

$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$senha = isset($_POST['senha']) ? $_POST['senha'] : null;
// var_dump($nome);
// exit();
validate('Nome', $nome, ['notNull' => 1, 'notEmptyString' => 1]);
validate('Senha', $senha, ['notNull' => 1, 'notEmptyString' => 1]);


$pdo = dbConnect();

$sql = 'SELECT * FROM usuarios where nome = :nome';
$statement = $pdo->prepare($sql);
$statement->bindParam(":nome", $nome, PDO::PARAM_STR);

$resultado = $statement->execute();

$usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);

if (count($usuarios) == 0){
    $erro = 'Usuário incorreto.';
    httpResponseExit ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $erro,
        'code' => 400
    ]);
}

$hashedPass = $usuarios[0]['senha'];
if(!password_verify($senha, $hashedPass)){
    $erro = 'Senha incorreta.';
    httpResponseExit ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $erro,
        'code' => 400
    ]);
}

$_SESSION["usuario_id"] = $usuarios[0]["id"];
// redirect("listamovimentos.php");
$success = "Logado com sucesso!";
httpResponseExit ([
    'headers' => [
        'Content-type:application/json',
        'charset=utf-8'
    ],
    'body' => $success,
    'code' => 200
]);
