<?php

setlocale(LC_TIME, "ptb");

define("defaultMessage", [
    'notNull' => 'Valor não pode ser vazio',
    'notEmptyString' => 'Valor não pode ser vazio',
    'notEquals' => 'Valor não é igual'
]);

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

/**
 * Função que recebe valor e um array de regras, conforme abaixo.
 * Regras disponíveis: null, emptyString
 */
function basicValidation($value, $rules){
    //se a regra existe e o valor for inválido...
    if(isset($rules['null']) && $value == null) {
        return [false, defaultMessage['notNull']];
    }
    if(isset($rules['emptyString']) && $value == '') {
        return [false, defaultMessage['notEmptyString']];
    }
    if(isset($rules['equals']) && $value != $rules['value']) {
        return [false, defaultMessage['notEquals']];
    }
    return [true,null];
}

/**
 * Recebe nome do campo, valor do campo, regras e destino caso erro
 * Valida campos, cria a mensagem de erro e dispara para o destino caso erro.
 */
function validate($name, $value, $rules, $destinoCasoErro){
    $validate = basicValidation($value, $rules);
    if(!$validate[0]) {
        redirect($destinoCasoErro . "?error=Campo $name: $validate[1]");
        exit();
    }
}