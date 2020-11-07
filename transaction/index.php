<?php
require_once("../utils.php");
allowOnly('logged', true);

$saldoinicial = 0;
$saldofinal = $saldoinicial;

// pegar do banco
$pdo = dbConnect();
$sql = 'select * from movimentos WHERE usuario_id = :usuario_id order by id asc;';
$statement = $pdo->prepare($sql);
$statement->bindParam(":usuario_id", $_SESSION["usuario_id"], PDO::PARAM_STR);
$resultado = $statement->execute();
$movimentos = $statement->fetchAll(PDO::FETCH_ASSOC);

// para cada registro, itero sobre ele
for ($i=0; $i < count($movimentos); $i++) { 
    //acumulando valor para o saldo
    if($movimentos[$i]['tipo'] == "Despesa"){
        $saldofinal = $saldofinal - $movimentos[$i]['valor'];
        $movimentos[$i]['saldo']=$saldofinal;
    }elseif($movimentos[$i]['tipo'] == "Receita"){
        $saldofinal = $saldofinal + $movimentos[$i]['valor'];
        $movimentos[$i]['saldo']=$saldofinal;
    }
    //convertendo a data para uma data legível
    $movimentos[$i]['datahoramovimentoReadable'] = converteData($movimentos[$i]['datahoramovimento']);
    //converter o valor para numero com virgula
    $movimentos[$i]['valorLegivel'] = converteSaldo($movimentos[$i]['valor']);
    //converter o saldo para numero com virgula
    $movimentos[$i]['saldoLegivel'] = converteSaldo($movimentos[$i]['saldo']);
}

httpResponseExit ([
    'headers' => [
        'Content-type:application/json',
        'charset=utf-8'
    ],
    'body' => [
        'movimentos' => $movimentos,
        'saldoinicial' => $saldoinicial,
        'saldofinal' => $saldofinal,
    ],
    'code' => 200
]);