<?php
session_start();
require_once "../utils.php";

$nome = $_POST['nome'];
$senha = $_POST['senha'];

validate('Nome', $nome, ['null' => 1, 'emptyString' => 1], '');
validate('Senha', $senha, ['null' => 1, 'emptyString' => 1], '');

$pdo = dbConnect();

$sql = 'SELECT * FROM usuarios where nome = :nome AND senha = :senha;';
$statement = $pdo->prepare($sql);
$statement->bindParam(":nome", $nome, PDO::PARAM_STR);
$statement->bindParam(":senha", $senha, PDO::PARAM_STR);

$resultado = $statement->execute();

$usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);

if (count($usuarios) == 0){
    $erro = 'Usuário e/ou senha incorretos.';
    redirect("index.php?error=$erro");
    exit();
}

$_SESSION ["usuario_id"] = $usuarios[0]["id"];
redirect("listamovimentos.php");
