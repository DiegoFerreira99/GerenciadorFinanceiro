<?php

setlocale(LC_TIME, "ptb");


/**
 * Exibe o dump da variavel passada na tela, formatada em um bloco pre (texto formatado).
 */
function dump($var)
{
    echo "<pre class='dump'>".var_export($var,true)."</pre>";
}

function converteData ($stringData){
    //se passar null, devolve null, pq n da pra converter null
    if(is_null($stringData)) return null;

    //monta um objeto nativo php do tipo datetime usando a data e hora
    $dateObject = \DateTime::createFromFormat('Y-m-d H:i:s',$stringData);

    //uso o método format do objeto datetime para criar uma data do meu jeito
    $dateTimeReadable = $dateObject->format('d/m/Y H:i');// H:i:s

    //gera o nome do dia da semana em ptbr
    $weekDay = strftime("%A", $dateObject->getTimestamp());

    $dateTimeReadable .= " ($weekDay)";
    return $dateTimeReadable;
}

function converteSaldo ($saldoOriginal){
    $saldoLegivel = number_format($saldoOriginal, 2, ',', '.');
    return $saldoLegivel;
}
function path($string){
    $caminho = "http://".$_SERVER["HTTP_HOST"]."\\".$string;
    return $caminho;
}

function redirect ($string){
    //pego a string passo como parametro para a funcao path, que vai me retornar a url completa
    $caminho = path($string);
    //com a url completa em mãos, eu uso o header para redirecionar.
    header("location:$caminho");
}

function dbConnect () {
    $connectionString = "mysql:host=localhost;dbname=ger_fin;charset=utf8mb4";
    try {
        $pdo = new \PDO($connectionString, "root", "123456", null);
        return $pdo;
    } catch (\PDOException $e) {
        dump('Connection failed: ' . $e->getMessage());
    }
}