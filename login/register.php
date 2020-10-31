<?php
require_once "../utils.php";

$nome = isset($_POST['nome']) ? $_POST['nome']:null; 
$senha = isset($_POST['senha']) ? $_POST['senha']:null;
$repitaSenha = isset($_POST['repitaSenha']) ? $_POST['repitaSenha']:null;

validate('Nome', $nome, ['notNull' => 1, 'notEmptyString' => 1]);
validate('Senha', $senha, ['notNull' => 1, 'notEmptyString' => 1]);
validate('Repita a senha', $repitaSenha, ['notNull' => 1, 'notEmptyString' => 1]);
validate('Repita a senha', $senha, ['equals' => 1, 'value' => $repitaSenha]);

$pdo = dbConnect();

$sql = 'SELECT * FROM usuarios WHERE nome = :nome;';
$statement = $pdo->prepare($sql);
$statement->bindParam(":nome", $nome, PDO::PARAM_STR);
$resultUsuarios = $statement->execute();
$usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);
if(count($usuarios) > 0){
    $erro = 'Nome em uso! Escolha outro!';
    httpResponseExit ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $erro,
        'code' => 400
    ]);
}

//hashear a senha
$hashedPass = password_hash($senha, PASSWORD_DEFAULT);
$sql2 = 'INSERT INTO usuarios (nome,senha) values (:nome, :senha);';
$statement2 = $pdo->prepare($sql2);
$statement2->bindParam(":nome", $nome, PDO::PARAM_STR);
$statement2->bindParam(":senha", $hashedPass, PDO::PARAM_STR);

$resultado = $statement2->execute();

if($resultado){
    $success = "Inserido com sucesso";
    httpResponseExit ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $success,
        'code' => 200
    ]);

} else {
    $erro = $statement->errorInfo();
    $erro = $erro[2];
    httpResponseExit ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $erro,
        'code' => 400
    ]);
}
