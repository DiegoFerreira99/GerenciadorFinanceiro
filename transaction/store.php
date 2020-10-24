<?php
session_start();
require_once "../utils.php";

//preparar os dados
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
$valor = isset($_POST['valor']) ? $_POST['valor'] : null;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
$datahoramovimento = isset($_POST['datahoramovimento']) ? $_POST['datahoramovimento'] : null;

if($descricao == null || $descricao == ""){
    $error = "O Campo descrição não pode estar vazio.";
    httpResponse ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $error,
        'code' => 400
    ]);
}

if($valor == null || $valor == ""){
    $error = "Escolha um valor válido.";
    httpResponse ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $error,
        'code' => 400
    ]);
}

if($tipo == null || $tipo == ""){
    $error = "Selecione o Tipo.";
    httpResponse ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $error,
        'code' => 400
    ]);
}

if($datahoramovimento == null || $datahoramovimento == ""){
    $error = "Selecione a data.";
    httpResponse ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $error,
        'code' => 400
    ]);
}

// gravar no banco
$pdo = dbConnect();

$sql = 'INSERT INTO movimentos (tipo,descricao,valor,datahoramovimento,usuario_id) values( :tipo, :descricao, :valor, :datahoramovimento, :usuario_id);';
$statement = $pdo->prepare($sql);
$statement->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$statement->bindParam(":descricao", $descricao, PDO::PARAM_STR);
$statement->bindParam(":valor", $valor, PDO::PARAM_STR);
$statement->bindParam(":datahoramovimento", $datahoramovimento, PDO::PARAM_STR);
$statement->bindParam(":usuario_id", $_SESSION["usuario_id"], PDO::PARAM_STR);

$resultado = $statement->execute();

if($resultado){
    //se der tudo certo, manda de volta para a lista de movimentos
    $success = 'Inserido com sucesso!';
    httpResponse ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $success,
        'code' => 201
    ]);

} else {
    //se não, volta pro formulario dizendo o error
    $error = $statement->errorrInfo();
    $error = $error[2];
    // dump($error);
    redirect("transaction/create.php?errorr=$error");
    httpResponse ([
        'headers' => [
            'Content-type:application/json',
            'charset=utf-8'
        ],
        'body' => $error,
        'code' => 500
    ]);
}

?>