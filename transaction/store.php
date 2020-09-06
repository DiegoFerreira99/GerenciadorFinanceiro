<?php

require_once "../utils.php";

//preparar os dados

echo dump($_POST); 

$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$tipo = $_POST['tipo'];
$datahoramovimento = $_POST['datahoramovimento'];

// gravar no banco
$pdo = dbConnect();

$sql = 'INSERT INTO transaction (tipo,descricao,valor,datahoramovimento) values( :tipo, :descricao, :valor, :datahoramovimento );';
$statement = $pdo->prepare($sql);
$statement->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$statement->bindParam(":descricao", $descricao, PDO::PARAM_STR);
$statement->bindParam(":valor", $valor, PDO::PARAM_STR);
$statement->bindParam(":datahoramovimento", $datahoramovimento, PDO::PARAM_STR);

$resultado = $statement->execute();

if($resultado){
    //se der tudo certo, manda de volta para a lista de movimentos
    $success = 'Inserido com sucesso!';
    redirect("index.php?success=$success");

} else {
    //se não, volta pro formulario dizendo o erro
    $erro = $statement->errorInfo();
    $erro = $erro[2];
    // dump($erro);
    redirect("transaction/create.php?error=$erro");
}

?>