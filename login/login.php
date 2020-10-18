<?php
session_start();
require_once "../utils.php";

$nome = $_POST['nome'];
$senha = $_POST['senha'];

validate('Nome', $nome, ['notNull' => 1, 'notEmptyString' => 1], '');
validate('Senha', $senha, ['notNull' => 1, 'notEmptyString' => 1], '');

$pdo = dbConnect();

$sql = 'SELECT * FROM usuarios where nome = :nome';
$statement = $pdo->prepare($sql);
$statement->bindParam(":nome", $nome, PDO::PARAM_STR);

$resultado = $statement->execute();

$usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);

if (count($usuarios) == 0){
    $erro = 'Usuário incorreto.';
    redirect("index.php?error=$erro");
    exit();
}

$hashedPass = $usuarios[0]['senha'];
if(!password_verify($senha, $hashedPass)){
    $erro = 'Senha incorreta.';
    redirect("index.php?error=$erro");
    exit();
}

$_SESSION["usuario_id"] = $usuarios[0]["id"];
redirect("listamovimentos.php");
