<?php

setlocale(LC_TIME, "ptb");

define("defaultMessage", [
    'nullValue' => 'Valor não pode ser vazio',
    'emptyString' => 'Valor não pode ser vazio',
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
    // $dateTimeReadable = $dateObject->format('d/m/Y H:i');// H:i:s
    $dateTimeReadable = $dateObject->format('d/m/Y');

    //gera o nome do dia da semana em ptbr
    $weekDay = strftime("%A", $dateObject->getTimestamp());

    $dateTimeReadable .= utf8_encode(" ($weekDay)");
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
    if(isset($rules['notNull']) && $value == null) {
        return [false, defaultMessage['nullValue']];
    }
    if(isset($rules['notEmptyString']) && $value == '') {
        return [false, defaultMessage['emptyString']];
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
function validate($name, $value, $rules, $restApi = true){
    $validate = basicValidation($value, $rules);
    if(!$validate[0]) {
        $error = "Campo $name: $validate[1]";
        if(!$restApi) {
            return $error;
        } else {
            httpResponseExit ([
                'headers' => [
                    'Content-type:application/json',
                    'charset=utf-8'
                ],
                'body' => $error,
                'code' => 400
            ]);
        }
    }
}


/**
 * @param string $neededStatus
 * Verifica se o usuário está logado e pode ver a página atual.
 * Needed Status pode ser guest para não logado ou logged para logado.
 */
function allowUser ($neededStatus)
{
    session_start();
    $defaultLogged = 'listamovimentos.php';
    $defaultGuest = 'index.php';

    $logged = false;
    if(
        isset($_SESSION["usuario_id"]) && 
        $_SESSION["usuario_id"] !== null && 
        $_SESSION["usuario_id"] !== 0)
        {
        $logged = true;
    }

    switch ($neededStatus) {
        case 'guest':
            if($logged){
                //se é visita e está logado, redireciona
                redirect($defaultLogged);
            }
            break;
        case 'logged':
            if(!$logged){
                //se devia setar logado mas não está, é visita, redireciona
                redirect($defaultGuest);
            }
            break;
        default:
            throw new \Exception("Tipo neededStatus inválido!", 1);
            break;
    }
}

function httpResponseExit ($args) {
    $headers = '';
    foreach ($args['headers'] as $key => $header) {
        $headers .= "$header;";
    }

    http_response_code($args['code']);
    header($headers);

    if(isset($args['body']) && $args['body'] !== null){
        $data['body'] = $args['body'];
    }
    // if(isset($args['error']) && $args['error'] !== null){
    //     $data['error'] = $args['error'];
    // }

    echo json_encode($data);
    
    exit();
}