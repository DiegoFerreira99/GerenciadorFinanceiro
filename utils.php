<?php
/**
 * Exibe o dump da variavel passada na tela, formatada em um bloco pre (texto formatado).
 */
function dump($var)
{
    echo "<pre class='dump'>".var_export($var,true)."</pre>";
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